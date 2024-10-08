<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    public function Colors()
    {
        return $this->hasMany(ProductColor::class);
    }

    public function wishlistedByUsers()
{
    return $this->belongsToMany(User::class, 'wishlist');
}

}
