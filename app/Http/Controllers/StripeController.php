<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;

class StripeController extends Controller
{
    public function checkout($orderId)
    {
        $order = Order::findOrFail($orderId);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = StripeSession::create([
            'mode' => 'payment',

            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => 'Order #' . $order->id,
                    ],
                    'unit_amount' => (int) ($order->total * 100),
                ],
                'quantity' => 1,
            ]],

            'metadata' => [
                'order_id' => $order->id,
            ],

            'success_url' => route('stripe.success', $order->id, true),
            'cancel_url'  => route('stripe.cancel', $order->id, true),
        ]);

        return redirect($session->url);
    }

    /**
     * STRIPE SUCCESS
     * Stock is reduced here
     */
    public function success(Order $order)
    {
        // Security: ensure user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        DB::beginTransaction();

        try {
            // Load order items with products
            $order->load('items.product');

            // 🔴 FINAL STOCK VALIDATION
            foreach ($order->items as $item) {
                if ($item->quantity > $item->product->stock) {
                    throw new \Exception(
                        "{$item->product->name} has only {$item->product->stock} items left."
                    );
                }
            }

            // Update order status
            if ($order->status !== 'paid') {
                $order->update([
                    'payment_method'    => 'stripe',
                    'status'            => 'paid',
                    'payment_intent_id' => request('payment_intent'),
                ]);
            }

            // ✅ DECREASE STOCK
            foreach ($order->items as $item) {
                $item->product->decrement('stock', $item->quantity);
            }

            // Clear cart
            CartItem::where('user_id', $order->user_id)->delete();

            DB::commit();

            // Optional mail (uncomment if needed)
            // Mail::to($order->user->email)->queue(new OrderPlacedMail($order));

            return redirect()->route('orders.success', $order->id)->with('success', 'yeah! Your Order placed successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            logger()->error($e->getMessage());

            return redirect()
                ->route('cart.view')
                ->with('error', $e->getMessage() ?: 'Payment succeeded but stock update failed.');
        }
    }

    public function cancel(Order $order)
    {
        // Security: ensure user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        if ($order->status !== 'paid') {
            $order->update([
                'status' => 'pending',
            ]);
        }

        return redirect()
            ->route('checkout.index')
            ->with('error', 'Payment was cancelled. You can try again.');
    }
}
