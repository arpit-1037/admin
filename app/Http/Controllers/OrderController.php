<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderPlacedMail;
use Yajra\DataTables\Facades\DataTables;

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
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Allow access only once after redirect
        if (!session()->has('order_success')) {
            return redirect()->route('guest.products.index');
        }

        // Remove flash so refresh won't work
        session()->forget('order_success');

        return view('order.success', compact('order'))->with('');
    }

    public function viewOrders()
    {
        $orders = Order::with('items.product')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

                 return view('users.view-orders', compact('orders'));
    }

    public function index(Request $request)
    {
        $orders = Order::where('user_id', auth::id())
            ->latest()
            ->with('items.product') // REQUIRED for cart-style
            ->get();

        return view('user.orders.index', compact('orders'));
    }
}
