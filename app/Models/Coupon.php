<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    protected $fillable = [
        'code',
        'discount',
        'quantity',
        'expired_at',
        'status'
    ];

    public $timestamps = false; 
}