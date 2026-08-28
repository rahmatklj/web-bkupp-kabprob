<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class HeroSlider extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = ['title', 'subtitle', 'image_url', 'button_text', 'button_url', 'order', 'is_active'];

    protected static function booted()
    {
        static::deleting(function ($slider) {
            self::deleteUploadFile($slider->image_url);
        });
    }
}

