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

            $orders = Order::select([
                'id',
                'name',
                'email',
                'total',
                'status',
                'created_at'
            ]);

            return DataTables::of($orders)
                ->addIndexColumn()

                ->editColumn('total', function ($order) {
                    return '₹' . number_format($order->total, 2);
                })

                ->editColumn('status', function ($order) {
                    $color = match ($order->status) {
                        'paid'    => 'bg-green-100 text-green-700',
                        'failed'  => 'bg-red-100 text-red-700',
                        default   => 'bg-yellow-100 text-yellow-700',
                    };

                    return '<span class="px-2 py-1 rounded text-sm ' . $color . '">'
                        . ucfirst($order->status) .
                        '</span>';
                })

                ->editColumn('created_at', function ($order) {
                    return $order->created_at->format('d M Y');
                })

                ->rawColumns(['status'])
                ->make(true);
        }


        return view('admin.orders.index');
    }
}
