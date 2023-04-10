<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;
    protected $fillable = [
        'orderId',
        'user_id',
        'status',
        'paymentStatus',
        'tax',
        'shipping',
        'subtotal',
        'total',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function billings()
    {
        return $this->belongsTo(Billing::class);
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}
