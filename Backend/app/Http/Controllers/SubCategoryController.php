<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\SubCategory;

class SubCategoryController extends Controller
{
    public function show(Category $category, SubCategory $subcategory)
    {
        return $subcategory->load('products.colors.images');
    }
}
