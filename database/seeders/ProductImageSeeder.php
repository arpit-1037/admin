<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::with('category')->get();

        foreach ($products as $product) {

            // Skip if image already exists
            if ($product->images()->exists() || ! $product->category) {
                continue;
            }

            $categorySlug = Str::slug($product->category->name);
            $imageDir = storage_path("app/public/products/{$categorySlug}");

            // Ensure category directory exists
            if (! File::exists($imageDir)) {
                File::makeDirectory($imageDir, 0755, true);
            }

            $imageName = $product->slug . '.svg';
            $imagePath = "{$imageDir}/{$imageName}";

            // Create SVG image if missing
            if (! File::exists($imagePath)) {
                File::put($imagePath, $this->generateSvg($product->name));
            }

            $productImage = ProductImage::create([
                'product_id' => $product->id,
                'path'       => "products/{$categorySlug}/{$imageName}",
                'is_primary' => true,
            ]);

            // Optional: if products table has primary_image_id
            if (Schema::hasColumn('products', 'primary_image_id')) {
                $product->update([
                    'primary_image_id' => $productImage->id
                ]);
            }
        }
    }

    private function generateSvg(string $text): string
    {
        $safeText = htmlspecialchars(substr($text, 0, 30), ENT_QUOTES);

        return <<<SVG
<svg xmlns="http://www.w3.org/2000/svg" width="600" height="600">
    <rect width="100%" height="100%" fill="#f3f4f6"/>
    <text x="50%" y="50%"
          dominant-baseline="middle"
          text-anchor="middle"
          font-size="24"
          fill="#374151"
          font-family="Arial, Helvetica, sans-serif">
        {$safeText}
    </text>
</svg>
SVG;
    }
}
