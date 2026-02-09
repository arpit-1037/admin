<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $orders = Order::query()
                ->join('users', 'orders.user_id', '=', 'users.id')
                ->select([
                    'orders.id',
                    'users.name as name',
                    'users.email as email',
                    'orders.total',
                    'orders.status',
                    'orders.payment_intent_id',
                    'orders.created_at',
                ]);

            return DataTables::of($orders)
                ->addIndexColumn()

                // total formatting
                ->editColumn('total', function ($order) {
                    return '₹' . number_format($order->total, 2);
                })

                // status badge
                ->editColumn('status', function ($order) {
                    $color = match ($order->status) {
                        'completed', 'paid' => 'bg-green-100 text-green-700',
                        'failed'            => 'bg-red-100 text-red-700',
                        default             => 'bg-yellow-100 text-yellow-700',
                    };

                    return '<span class="px-3 py-1 rounded-full text-sm font-medium ' . $color . '">'
                        . ucfirst($order->status) .
                        '</span>';
                })

                // payment_intent_id nullable handling
                ->editColumn('payment_intent_id', function ($order) {
                    return $order->payment_intent_id ?? '<span class="text-gray-400">—</span>';
                })

                // date formatting
                ->editColumn('created_at', function ($order) {
                    return \Carbon\Carbon::parse($order->created_at)->format('d M Y');
                })

                ->rawColumns(['status', 'payment_intent_id'])
                ->make(true);
        }

        return view('admin.orders.index');
    }
}
