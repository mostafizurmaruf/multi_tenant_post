<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends BaseModel
{
    protected $fillable = [
        'name',
        'sku',
        'price',
        'stock_quantity',
        'low_stock_threshold'
    ];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
