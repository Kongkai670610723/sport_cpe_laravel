<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';
    protected $primaryKey = 'order_id';
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'promo_id',
        'order_date',
        'total_amount',
        'discount_amount',
        'net_amount',
        'order_status',
        'shipping_address',
        'payment_method',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function promotion()
    {
        return $this->belongsTo(Promotion::class, 'promo_id', 'promo_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }
}
