<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class Page extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = ['title', 'slug', 'content', 'image', 'is_published'];

    protected static function booted()
    {
        static::deleting(function ($page) {
            self::deleteUploadFile($page->image);
        });
    }
}

