<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $table = 'promotions';
    protected $primaryKey = 'promo_id';
    public $timestamps = false;

    protected $fillable = [
        'promo_code',
        'description',
        'discount_type',
        'discount_value',
        'min_order_amount',
        'start_date',
        'end_date',
        'is_active',
    ];

    public function products()
    {
        return $this->belongsToMany(
            Product::class,
            'promotion_products',
            'promo_id',
            'product_id'
        );
    }
}

