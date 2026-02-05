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
            ->when(Auth::check(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->firstOrFail();

        Mail::to($order->user->email)->queue(
            new OrderPlacedMail($order)
        );

        return view('order.success', compact('order'));
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
        if ($request->ajax()) {

            $orders = Order::where('user_id', auth::id())
                ->latest()
                ->select([
                    'id',
                    'user_id',
                    'total',
                    'status',
                    'payment_intent_id',
                    'created_at',
                ]);

            return DataTables::of($orders)
                ->addIndexColumn()

                ->editColumn(
                    'total',
                    fn($order) =>
                    '₹' . number_format($order->total, 2)
                )

                ->editColumn('status', function ($order) {
                    $color = match ($order->status) {
                        'completed', 'paid' => 'bg-green-100 text-green-700',
                        'failed', 'cancelled' => 'bg-red-100 text-red-700',
                        default => 'bg-yellow-100 text-yellow-700',
                    };

                    return '<span class="px-3 py-1 rounded-full text-sm font-medium ' . $color . '">'
                        . ucfirst($order->status) .
                        '</span>';
                })

                ->editColumn(
                    'payment_intent_id',
                    fn($order) =>
                    $order->payment_intent_id ?? '—'
                )

                ->editColumn(
                    'created_at',
                    fn($order) =>
                    $order->created_at->format('d M Y')
                )

                // ->addColumn(
                //     'action',
                //     fn($order) =>
                //     '<a href="' . route('orders.show', $order->id) . '"
                //    class="text-blue-600 hover:underline">View</a>'
                // )

                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('user.orders.index');
    }
}
