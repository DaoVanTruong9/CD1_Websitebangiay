<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
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
}
