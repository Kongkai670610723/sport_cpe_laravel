<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'product_id';
    public $timestamps = false;

    protected $fillable = [
        'category_id',
        'name',
        'brand',
        'price',
        'stock_quantity',
        'image_url',
        'description',
        'is_featured',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    public function promotions()
    {
        return $this->belongsToMany(
            Promotion::class,
            'promotion_products',
            'product_id',
            'promo_id'
        );
    }
}

