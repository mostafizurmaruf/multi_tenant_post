<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends BaseModel
{
    protected $fillable = ['user_id', 'status', 'total_amount'];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
