<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'icon',
        'summary',
        'requirements',
        'procedure',
        'service_time',
        'cost',
        'external_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
