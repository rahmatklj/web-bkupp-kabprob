<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class PublicDocument extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = ['title', 'category', 'file_path', 'file_url', 'download_count', 'is_published'];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    protected static function booted()
    {
        static::deleting(function ($doc) {
            self::deleteUploadFile($doc->file_url);
            self::deleteUploadFile($doc->file_path);
        });
    }
}

