<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorImage;
use App\Models\SubCategory;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Category::factory(5)->create()->each(function ($category) {
            SubCategory::factory(rand(1, 3))->create(['category_id' => $category->id]);
        });
        Brand::factory(5)->create();
        Product::factory(50)->create()->each(function ($product) {
            // Create colors for each product
            $colors = ProductColor::factory(rand(1, 5))->create(['product_id' => $product->id]);

            // Create images for each color
            $colors->each(function ($color) {
                ProductColorImage::factory(rand(1, 3))->create(['product_color_id' => $color->id]);
            });
        });
    }
}
