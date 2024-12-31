<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'stock',
    ];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }


    public function orderItems()
    {
        return $this->hasMany(OrderItems::class, 'product_id');
    }
}
