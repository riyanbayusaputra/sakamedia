<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'slug',
        'category',
        'badge',
        'icon',
        'name',
        'description',
        'speed',
        'unit',
        'old_price',
        'discount',
        'price',
        'is_popular',
        'features',
        'sort_order',
        'is_active',
    ];

    protected $casts = [
        'features' => 'array',     // otomatis convert JSON <-> array PHP
        'is_popular' => 'boolean',
        'is_active' => 'boolean',
    ];
}