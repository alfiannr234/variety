<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    public function getTotalPriceAttribute()
    {
        return $this->attributes['total_amount'] ?? $this->attributes['total_price'] ?? 0;
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
