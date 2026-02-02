<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'required|integer|min:1',
        ]);

        $user = auth::user();
        $quantity = $request->quantity;

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // ✅ Add selected quantity
            $cartItem->increment('quantity', $quantity);
        } else {
            CartItem::create([
                'user_id'    => $user->id,
                'product_id' => $request->product_id,
                'quantity'   => $quantity, // ✅ real counter value
            ]);
        }

        return back()->with('success', 'Product added to cart');
    }
    public function index()
    {
        $cartItems = CartItem::with('product')
            ->where('user_id', auth::id())
            ->get();

        $total = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('cart.index', compact('cartItems', 'total'));
    }

    public function destroy(CartItem $cartItem)
    {
        // Security check: user can delete only their own cart item
        if ($cartItem->user_id !== auth::id()) {
            abort(403);
        }

        $cartItem->delete();

        return redirect()
            ->route('cart.view')
            ->with('success', 'Item removed from cart');
    }
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
}
