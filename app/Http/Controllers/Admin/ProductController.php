<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;
 use App\Models\ProductImage;
use Illuminate\Support\Facades\Storage;


class ProductController extends Controller
{
   

public function destroyImage(ProductImage $image)
{
    // Do not allow deleting last image
    if ($image->product->images()->count() <= 1) {
        return back()->with('error', 'Product must have at least one image.');
    }

    // If primary image is deleted, assign a new primary
    if ($image->is_primary) {
        $newPrimary = $image->product->images()
            ->where('id', '!=', $image->id)
            ->first();

        if ($newPrimary) {
            $newPrimary->update(['is_primary' => true]);
        }
    }

    // Delete file from storage
    Storage::disk('public')->delete($image->path);

    // Delete DB record
    $image->delete();

    return back()->with('success', 'Image deleted successfully.');
}
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return DataTables::of(Product::with('category', 'images')->latest())
                ->addIndexColumn()
                ->addColumn('image', function ($product) {
                    $image = $product->images->first();
                    return $image
                        ? '<img src="' . asset('storage/' . $image->path) . '" class="w-12 h-12 rounded">'
                        : '-';
                })
                ->addColumn('category', fn($p) => $p->category->name ?? '-')
                ->addColumn(
                    'status',
                    fn($p) =>
                    $p->is_active
                        ? '<span class="text-green-600">Active</span>'
                        : '<span class="text-red-600">Inactive</span>'
                )
                ->addColumn('actions', function ($product) {
                    return '
                    <a href="' . route('admin.products.edit', $product) . '"
                       class="text-blue-600 mr-3">Edit</a>

                    <form method="POST"
                          action="' . route('admin.products.destroy', $product) . '"
                          class="inline">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="button"
                                onclick="confirmDelete(this)"
                                class="text-red-600">
                            Delete
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['image', 'status', 'actions'])
                ->make(true);
        }

        return view('admin.products.index');
    }


    public function create()
    {
        $categories = Category::where('is_active', true)->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'required|in:0,1',
            'images'      => 'required|array|min:1',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $product = Product::create([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'is_active'   => $request->boolean('is_active'),
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

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product created successfully.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->get();
        $product->load('images');

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'        => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'is_active'   => 'required|in:0,1',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'primary_image' => 'nullable|exists:product_images,id',
        ]);

        $product->update([
            'name'        => $request->name,
            'slug'        => Str::slug($request->name),
            'category_id' => $request->category_id,
            'description' => $request->description,
            'price'       => $request->price,
            'stock'       => $request->stock,
            'is_active'   => $request->boolean('is_active'),
        ]);

        // Add new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create([
                    'path' => $path,
                    'is_primary' => false,
                ]);
            }
        }

        // Update primary image
        if ($request->primary_image) {
            $product->images()->update(['is_primary' => false]);
            $product->images()
                ->where('id', $request->primary_image)
                ->update(['is_primary' => true]);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully.');
    }
}
