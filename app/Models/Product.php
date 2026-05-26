<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{

    use HasFactory;
    
    protected $fillable = [
    'name',
    'brand',
    'size',
    'price',
    'image',
    'description',
    'is_sale',
    'is_featured'
];

    public function inventory()
    {
        return $this->hasOne(Inventory::class);
    }

    public function reviews()
    {
        return $this->hasMany(\App\Models\Review::class);
    }
}
