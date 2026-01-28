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
            : $this->handleStripe();
    }

    private function handleCOD(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }

        // $cartItems = CartItem::where('user_id', Auth::id())->get();

        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

//         dd($cartItems->sum(fn ($item) =>
//     $item->product->price * $item->quantity
// )

//         );


        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });


        if ($cartItems->isEmpty()) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        DB::beginTransaction();

        try {
            // 1️⃣ Calculate total
            // dd($cartItems->toArray());

            $total = $cartItems->sum(fn ($item) =>
    $item->product->price * $item->quantity
);

            // dd($total);

            // 2️⃣ Create Order
            $order = Order::create([
                'user_id'           => Auth::id(),
                'name'              => $user->name,
                'email'             => $user->email,
                'address'           => $request->address ?? $user->address ?? 'N/A',
                'total'             => $total,
                'status'            => 'pending',
                'payment_intent_id' => null, // COD
            ]);

            // dd($order->toArray());

            // 3️⃣ Order Items
            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item->product_id,
                    'price'     => (float) $item->product->price,
                    'quantity'  => $item->quantity,
                ]);

                
            }
        //  dd($item->toArray());  
            // 4️⃣ Clear cart_items (THIS IS THE KEY FIX)
            CartItem::where('user_id', Auth::id())->delete();

            DB::commit();

            // 5️⃣ Redirect to success
            return redirect()->route('orders.success', $order->id);
        } catch (\Throwable $e) {
            DB::rollBack();

            // \Log::error('COD order failed', [
            //     'user_id' => Auth::id(),
            //     'error'   => $e->getMessage(),
            // ]);

            

            return redirect()->back()->with('error', 'Order failed.');
        }
    }

    private function handleStripe()
    {
        $cartItems = CartItem::where('user_id', Auth::id())->get();

        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id'         => Auth::id(),
                'total_amount'   => $cartItems->sum(fn($i) => $i->price * $i->quantity),
                'payment_method' => 'stripe',
                'payment_status' => 'pending',
                'status'         => 'pending',
            ]);

            foreach ($cartItems as $item) {
                OrderItem::create([
                    'order_id'    => $order->id,
                    'product_id' => $item->product_id,
                    'price'      => $item->price,
                    'quantity'   => $item->quantity,
                ]);
            }

            DB::commit();

            // Redirect to Stripe Checkout
            return redirect()->route('stripe.checkout', $order->id);
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Stripe initiation failed.');
        }
    }
}
