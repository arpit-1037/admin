<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;


class ProductController extends Controller
{
public function index(Request $request)
{
    if ($request->ajax()) {

        $products = Product::with(['category', 'primaryImage'])
            ->select('products.*');

        return DataTables::of($products)
            ->addIndexColumn()

            ->addColumn('image', function ($product) {
                if ($product->primaryImage) {
                    return '<img src="' . asset('storage/' . $product->primaryImage->path) . '" class="h-12 w-12 rounded object-cover">';
                }
                return '<span class="text-gray-400 text-sm">No Image</span>';
            })

            ->addColumn('category', function ($product) {
                return $product->category->name ?? '-';
            })

            ->addColumn('status', function ($product) {
                return $product->is_active
                    ? '<span class="text-green-600 font-semibold">Active</span>'
                    : '<span class="text-red-600 font-semibold">Inactive</span>';
            })

            ->addColumn('actions', function ($product) {
                return '
                    <form method="POST"
                          action="' . route('admin.products.destroy', $product) . '"
                          onsubmit="return confirm(\'Delete product?\')"
                          class="inline">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="text-red-600 hover:underline">
                            Delete
                        </button>
                    </form>
                ';
            })

            ->rawColumns(['image', 'status', 'actions'])
            ->make(true);
    }

    // IMPORTANT: Do NOT pass $products
    return view('admin.products.index');
}


    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
      // dd($request);    
        $request->validate([
        'name'        => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'description' => 'nullable|string',
        'price'       => 'required|numeric|min:0',
        'stock'       => 'required|integer|min:0',
        'images'      => 'required|array|min:1',
        'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);
        // dd('here'); 

        $product = Product::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price' => $request->price,
            'stock' => $request->stock,
            'is_active' => true,
        ]);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('products', 'public');

                $product->images()->create([
                    'path' => $path,
                    'is_primary' => $index === 0,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
