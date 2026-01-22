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
        ]);

        $user = auth::user();

        $cartItem = CartItem::where('user_id', $user->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            $cartItem->increment('quantity');
        } else {
            CartItem::create([
                'user_id' => $user->id,
                'product_id' => $request->product_id,
                'quantity' => 1,
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
}
