<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class NewsItem extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = [
        'title', 'slug', 'summary', 'content', 'image_url', 'category', 'published_at', 'views', 'is_featured', 'is_published'
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function ($news) {
            self::deleteUploadFile($news->image_url);
        });
    }
}

