<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'rating',
        'review',
        'name',
        'email',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}