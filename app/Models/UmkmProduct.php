<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UmkmProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'owner_name',
        'category',
        'district',
        'description',
        'price',
        'price_unit',
        'phone',
        'image',
        'website_url',
        'is_featured',
        'is_verified'
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_verified' => 'boolean',
        'price' => 'decimal:2',
    ];
}
