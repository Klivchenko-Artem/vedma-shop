<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'slug', 'description',
        'composition', 'price', 'images', 'is_popular', 'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
    ];
}
