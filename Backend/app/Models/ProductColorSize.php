<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductColorSize extends Model
{
    use HasFactory;
    protected $table = 'product_color_size';


    public function productColor()
{
    return $this->belongsTo(ProductColor::class);
}

}
