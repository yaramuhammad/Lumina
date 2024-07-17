<?php

namespace App\Http\Controllers;

use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        return Product::with('colors.images')->get();
    }

    public function show(Product $product)
    {
        return $product->load('colors.images');
    }
}
