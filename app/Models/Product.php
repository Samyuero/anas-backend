<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'short_description',
        'description',
        'regular_price',
        'sale_price',
        'SKU',              // Uppercase to match your database column
        'stock_status',
        'featured',
        'quantity',
        'image',
        'images',
        'gallery_images',
        'category_id',
        'brand_id',
        'weight',
        'dimensions',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function reviews()
{
    return $this->hasMany(Review::class);
}
}