<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id', 'name', 'slug', 'description',
        'composition', 'price', 'images', 'is_popular', 'sort_order',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
        'is_popular' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
}
