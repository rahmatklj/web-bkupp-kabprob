<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class Gallery extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = [
        'title',
        'type',
        'category',
        'file_path',
        'youtube_url',
        'caption',
        'is_active'
    ];

    protected $appends = ['images', 'cover_image', 'photo_count'];

    protected static function booted()
    {
        static::deleting(function ($gallery) {
            self::deleteUploadFile($gallery->file_path);
        });
    }

    public function getImagesAttribute()
    {
        if (empty($this->file_path)) {
            return [];
        }
        
        $val = $this->file_path;
        // Unwrap double or triple encoded JSON strings recursively
        while (is_string($val)) {
            $decoded = json_decode($val, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                if (is_array($decoded) || is_string($decoded)) {
                    $val = $decoded;
                } else {
                    break;
                }
            } else {
                break;
            }
        }
        
        if (is_array($val)) {
            return array_values(array_filter($val));
        }
        
        return [(string)$val];
    }

    public function getCoverImageAttribute()
    {
        $imgs = $this->images;
        if (!empty($imgs) && isset($imgs[0]) && !empty($imgs[0])) {
            return $imgs[0];
        }
        return 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?q=80&w=600&auto=format&fit=crop';
    }

    public function getPhotoCountAttribute()
    {
        return count($this->images);
    }
}

