<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Stripe\Stripe;
use Stripe\Checkout\Session as StripeSession;
use Illuminate\Support\Facades\Auth;

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

    public function success(Order $order)
    {
        if ($order->payment_status !== 'paid') {
            $order->update([
                // 'payment_status'    => 'paid',
                'payment_method'     => 'stripe',
                'status'            => 'paid',
                'payment_intent_id' => request('payment_intent'),
            ]);
        }
        //  Mail::to($order->user->email)->send(new OrderPlacedMail($order));

        return redirect()->route('orders.success', $order->id);
    }


    public function cancel(Order $order)
    {
        // Security: ensure user owns the order
        if ($order->user_id !== Auth::id()) {
            abort(403);
        }

        // Optional: update order status if needed
        if ($order->payment_status !== 'paid') {
            $order->update([
                'payment_status' => 'pending',
                'status'         => 'pending',
            ]);
        }

        // Redirect user back to checkout or cart
        return redirect()
            ->route('checkout.index')
            ->with('error', 'Payment was cancelled. You can try again.');
    }
}
