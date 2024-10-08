<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColor extends Model
{
    use HasFactory;

    public function Images()
    {
        return $this->hasMany(ProductColorImage::class);
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class,'product_color_size')->withPivot('id');
    }

}
