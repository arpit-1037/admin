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
{ //
    public function index()
    {
        //  dd('here');
        return view('checkout.index');
    }

    public function store(Request $request)
    {

        dd('here');
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:cod',
        ]);

        $user = auth::user();

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.view');
        }

        DB::beginTransaction();

        try {
            // 1️⃣ Calculate total
            $total = $cartItems->sum(function ($item) {
                return $item->product->price * $item->quantity;
            });

            // 2️⃣ Create Order
            $order = Order::create([
                'user_id' => $user->id,
                'address_id' => $request->address_id,
                'total' => $total,
                'payment_method' => 'cod',
                'status' => 'pending', // COD = pending
            ]);

            // 3️⃣ Create Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item->product_id,
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                ]);
            }

            // 4️⃣ Clear Cart
            CartItem::where('user_id', $user->id)->delete();
            dd($user->email);
            DB::commit();

            // 5️⃣ Send Email
            Mail::to($user->email)->send(new OrderPlacedMail($order));

            return redirect()->route('order.success', $order->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
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

    private function handleCOD(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        $cartItems = CartItem::with('product')
            ->where('user_id', $user->id)
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        $total = $cartItems->sum(
            fn($item) =>
            $item->product->price * $item->quantity
        );

        DB::beginTransaction();

        try {
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
            }

            CartItem::where('user_id', $user->id)->delete();
            DB::afterCommit(function () use ($user, $order) {
                $order->load('items.product');

                // Mail::to($user->email)->queue(
                //     new OrderPlacedMail($order)
                // );
            });
            Mail::to($user->email)->queue(
                new OrderPlacedMail($order)
            );
            return redirect()->route('orders.success', $order->id);
        } catch (\Throwable $e) {
            DB::rollBack();

            // TEMP: log error to debug
            logger()->error($e->getMessage());

            return redirect()->back()->with('error', 'Order failed.');
        }
    }

    private function handleStripe(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

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
                'status'            => 'pending',
                'payment_intent_id' => 'stripe', // update after payment success
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'price'      => (float) $item->product->price,
                    'quantity'   => $item->quantity,
                ]);
            }

            CartItem::where('user_id', $user->id)->delete();

            DB::commit();

            return redirect()->route('stripe.checkout', $order->id);
        } catch (\Throwable $e) {
            DB::rollBack();

            logger()->error($e->getMessage());

            return redirect()->back()->with('error', 'Stripe initiation failed.');
        }
    }
}
