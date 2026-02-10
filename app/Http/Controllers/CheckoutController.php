<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\CartItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;

class CheckoutController extends Controller
{
    public function index()
    {
        return view('checkout.index');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:cod,stripe',
        ]);

        return $request->payment_method === 'cod'
            ? $this->handleCOD($request)
            : $this->handleStripe($request);
    }

    /**
     * CASH ON DELIVERY
     * Stock is reduced here
     */
    private function handleCOD(Request $request)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            // 🔴 FINAL STOCK VALIDATION
            foreach ($cartItems as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception(
                        "{$item->product->name} has only {$item->product->stock} items left."
                    );
                }
            }

            $total = round($cartItems->sum(
                fn($item) => $item->product->price * $item->quantity
            ));

            $order = Order::create([
                'user_id'           => $user->id,
                'total'             => $total,
                'status'            => 'pending',
                'payment_intent_id' => null,
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'price'      => (float) $item->product->price,
                    'quantity'   => $item->quantity,
                ]);

                // ✅ DECREASE STOCK (COD ONLY)
                $item->product->decrement('stock', $item->quantity);
            }

            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            Mail::to($user->email)->queue(
                new OrderPlacedMail($order->load('items.product'))
            );

            return redirect()
                ->route('orders.success', $order->id)
                ->with('order_success', 'Your order has been placed successfully!');
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error($e->getMessage());

            return redirect()->back()->with(
                'error',
                $e->getMessage() ?: 'Order failed.'
            );
        }
    }

    /**
     * STRIPE
     * Stock update will be handled later (as requested)
     */
    private function handleStripe(Request $request)
    {
        $user = Auth::user();
        if (!$user) abort(403);

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(
            fn($item) => $item->product->price * $item->quantity
        );

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'           => $user->id,
                'total'             => $total,
                'status'            => 'paid',
                'payment_intent_id' => 'stripe',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'price'      => (float) $item->product->price,
                    'quantity'   => $item->quantity,
                ]);
            }

            DB::commit();

            return redirect()->route('stripe.checkout', $order->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error($e->getMessage());

            return redirect()->back()->with(
                'error',
                'Stripe initiation failed.'
            );
        }
    }
}
