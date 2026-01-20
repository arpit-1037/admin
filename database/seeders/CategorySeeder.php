<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Electronics',
            'Fashion',
            'Home Appliances',
            'Books',
            'Sports',
            'Toys',
            'Groceries',
            'Beauty',
            'Furniture',
            'Automotive',
            'Health',
            'Stationery',
            'Footwear',
            'Watches',
            'Jewelry',
            'Music',
            'Gaming',
            'Office Supplies',
            'Kitchen',
            'Outdoor'
        ];

        foreach ($categories as $name) {
            Category::create([
                'name'      => $name,
                'slug'      => Str::slug($name),
                'is_active' => true,
            ]);
        }
    }
}
