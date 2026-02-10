<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

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

                // Format total
                ->editColumn('total', function ($order) {
                    return '₹' . number_format($order->total, 2);
                })

                // Status badge
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

                // Action button
                ->addColumn('action', function ($order) {
                    $isPaid = $order->status === 'paid';

                    return '
        <button
            class="toggle-status px-3 py-1 text-sm font-semibold rounded-full
            ' . ($isPaid ? 'bg-red-500 hover:bg-red-700' : 'bg-green-600 hover:bg-green-700') . '
            text-white"
            data-id="' . $order->id . '">
            ' . ($isPaid ? 'Mark Pending' : 'Mark Paid') . '
        </button>
    ';
                })

                // Nullable payment intent
                ->editColumn('payment_intent_id', function ($order) {
                    return $order->payment_intent_id
                        ? e($order->payment_intent_id)
                        : '<span class="text-gray-400">—</span>';
                })

                // Date format
                ->editColumn('created_at', function ($order) {
                    return Carbon::parse($order->created_at)->format('d M Y');
                })

                // Columns containing HTML
                ->rawColumns(['status', 'action', 'payment_intent_id'])

                ->make(true);
        }

        return view('admin.orders.index');
    }

    public function toggleStatus(Order $order)
    {
        $order->update([
            'status' => $order->status === 'paid' ? 'pending' : 'paid'
        ]);

        return response()->json(['success' => true]);
    }
}
