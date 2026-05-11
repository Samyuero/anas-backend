<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slide extends Model
{
    protected $fillable = [
        'tagline',
        'title',
        'subTitle',
        'link',
        'image',
        'status',
    ];
}
