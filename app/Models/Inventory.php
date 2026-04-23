<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inventory extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'sold_quantity',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function updateStatus()
    {
        if ($this->quantity <= 0) {
            $this->status = 'out_of_stock';
        } elseif ($this->quantity <= 5) {
            $this->status = 'low_stock';
        } else {
            $this->status = 'in_stock';
        }
    }
}