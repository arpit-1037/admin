<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class CatalogController extends Controller
{
    public function index()
    {
        $products = Product::with('primaryImage', 'category')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);

        return view('guest.products.index', compact('products'));
    }
}
