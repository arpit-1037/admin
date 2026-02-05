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
    public function clear()
    {
        $user = Auth::user();

        if (!$user) {
            abort(403);
        }
        CartItem::where('user_id', $user->id)->delete();
        // dd('here');

        return redirect()->route('cart.view')
            ->with('success', 'Cart cleared successfully.');
    }

    public function handleAjax(Request $request)
    {
        // dd("sasss");
        $user = auth::user();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        $action    = $request->input('action');
        $productId = (int) $request->input('product_id', 0);
        $quantity  = max(1, (int) $request->input('quantity', 1));

        $success = false;
        $message = 'Invalid action';

        switch ($action) {

            case 'add':
                if ($productId > 0) {
                    $cartItem = CartItem::where('user_id', $user->id)
                        ->where('product_id', $productId)
                        ->first();

                    if ($cartItem) {
                        $cartItem->increment('quantity', $quantity);
                    } else {
                        CartItem::create([
                            'user_id'    => $user->id,
                            'product_id' => $productId,
                            'quantity'   => $quantity,
                        ]);
                    }

                    $success = true;
                    $message = 'Added to cart';
                }
                break;

            case 'remove':
                if ($productId > 0) {
                    $deleted = CartItem::where('user_id', $user->id)
                        ->where('product_id', $productId)
                        ->delete();

                    $success = $deleted > 0;
                    $message = $success ? 'Removed from cart' : 'Item not found';
                }
                break;

            case 'clear_all':
                CartItem::where('user_id', $user->id)->delete();
                $success = true;
                $message = 'Cart cleared';
                break;

            case 'update':
                if ($productId > 0 && $quantity > 0) {
                    $updated = CartItem::where('user_id', $user->id)
                        ->where('product_id', $productId)
                        ->update(['quantity' => $quantity]);

                    $success = $updated > 0;
                    $message = $success ? 'Quantity updated' : 'Item not found';
                }
                break;
        }

        $cartCount = CartItem::where('user_id', Auth::id())
            ->select('product_id')
            ->distinct()
            ->count();
            
        return response()->json([
            'success'    => $success,
            'message'    => $message,
            'cart_count' => $cartCount,
        ]);
    }
}
