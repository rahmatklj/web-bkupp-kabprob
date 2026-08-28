<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class RelatedLink extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = ['title', 'image_url', 'url', 'order', 'is_active'];

    protected static function booted()
    {
        static::deleting(function ($link) {
            self::deleteUploadFile($link->image_url);
        });
    }
}

