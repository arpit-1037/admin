<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;

class OrderController extends Controller
{
    public function summary(Request $request)
    {

        $cartItems = CartItem::with('product')
            ->where('user_id', auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });


        // $addresses = auth::user()->addresses;
        $addresses = auth::user()->addresses ?? collect(); // assumed relation

        return view('order.summary', compact(
            'cartItems',
            'total',
            'addresses'
        ));
    }

    public function success($orderId)
    {
        $order = Order::with(['items.product'])
            ->where('id', $orderId)
            ->when(Auth::check(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        Mail::to($order->user->email)->queue(
            new OrderPlacedMail($order)
        );

        return view('order.success', compact('order'));
    }
}
