<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
     
class UserDashboardController extends Controller
{
    //
    public function index(Request $request)
    {
        // Temporary response until Blade is added
        // return response('User Dashboard', 200);
        $products = Product::with('primaryImage', 'category')
            ->where('is_active', true)
            ->latest()
            ->paginate(12);
        
        return view('guest.products.index', compact('products'));
    }
}
