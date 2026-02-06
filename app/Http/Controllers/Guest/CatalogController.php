<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\Category;

class CatalogController extends Controller
{
    // public function index()
    // {
    //     $products = Product::with('primaryImage', 'category')
    //         ->where('is_active', true)
    //         ->latest()
    //         ->paginate(12);

    //     return view('guest.products.index', compact('products'));
    // }

    public function index(Request $request)
    {
        $search     = $request->input('search', '');
        $categoryId = (int) $request->input('category_id', 0);
        $page       = (int) $request->input('page_num', 1);

        $query = Product::with('primaryImage', 'category')
            ->where('is_active', true);

        // 🔍 Search (NAME + DESCRIPTION) ✅ FIX
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // 📂 Category filter (unchanged)
        if ($categoryId > 0) {
            $query->where('category_id', $categoryId);
        }

        $products = $query
            ->latest()
            ->paginate(12, ['*'], 'page', $page);

        // AJAX response (LEGACY STYLE) — unchanged
        if ($request->ajax()) {
            return response()->json([
                'html'  => view(
                    'guest.products.partials.products-grid',
                    compact('products')
                )->render(),
                'count' => $products->total(),
            ]);
        }

        $cat = Category::latest()->get();

        // Normal page load — unchanged
        return view('guest.products.index', [
            'products' => $products,
            'cat'      => $cat,
        ]);
    }

    // public function view()
    // {
    //     $products = Product::with('primaryImage', 'category')
    //         ->where('is_active', true)
    //         ->latest()
    //         ->paginate(12);
    //      return view('guest.products.index', compact('products'));
    // }
}
