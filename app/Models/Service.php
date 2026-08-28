<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class Service extends Model
{
    use HasFactory, DeletesUploadFiles;

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
        'location',
        'external_url',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function ($service) {
            self::deleteUploadFile($service->external_url);
        });
    }
}

