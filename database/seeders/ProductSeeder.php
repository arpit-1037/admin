<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $categories = Category::pluck('id')->toArray();

        if (empty($categories)) {
            $this->command->warn('No categories found. Run CategorySeeder first.');
            return;
        }

        for ($i = 1; $i <= 50; $i++) {
            $name = "Sample Product {$i}";

            Product::create([
                'category_id' => $categories[array_rand($categories)],
                'name'        => $name,
                'slug'        => Str::slug($name) . '-' . $i,
                'description' => 'This is a sample product description.',
                'price'       => rand(100, 5000),
                'stock'       => rand(1, 100),
                'is_active'   => true,
            ]);
        }
    }
}
