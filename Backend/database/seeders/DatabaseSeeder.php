<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\ProductColor;
use App\Models\ProductColorImage;
use App\Models\SubCategory;
use App\Models\Size;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use DB;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Size::insert(['size' => 'sm']);
        Size::insert(['size' => 'md']);
        Size::insert(['size' => 'lg']);
        Size::insert(['size' => 'xl']);
        Size::insert(['size' => '2xl']);

        Category::factory(5)->create()->each(function ($category) {
            SubCategory::factory(rand(1, 3))->create(['category_id' => $category->id]);
        });
        Brand::factory(5)->create();
        Product::factory(50)->create()->each(function ($product) {
            $colors = ProductColor::factory(rand(1, 5))->create(['product_id' => $product->id]);
            $colors->each(function ($color) {
                ProductColorImage::factory(rand(1, 3))->create(['product_color_id' => $color->id]);
            });
        });

        DB::table('product_color_size')->insert([
            ['product_color_id' => 1, 'size_id' => 1, 'quantity' => 100],
            ['product_color_id' => 1, 'size_id' => 2, 'quantity' => 150],
            ['product_color_id' => 2, 'size_id' => 1, 'quantity' => 200],
        ]);


    }
}
