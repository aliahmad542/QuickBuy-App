<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'store_id',
        'description',
        'image',
        'quantity'
    ];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function carts()
    {
        return $this->belongsToMany(Cart::class, 'carts', 'product_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'product_id');
    }
}
