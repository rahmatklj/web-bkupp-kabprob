<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class NavigationMenu extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = ['title', 'url', 'parent_id', 'order', 'target', 'is_active'];

    protected static function booted()
    {
        static::deleting(function ($menu) {
            if ($menu->url && str_contains($menu->url, '/uploads/menus/')) {
                self::deleteUploadFile($menu->url);
            }
        });
    }

    public function children()
    {
        return $this->hasMany(NavigationMenu::class, 'parent_id')->where('is_active', true)->orderBy('order', 'asc');
    }

    public function parent()
    {
        return $this->belongsTo(NavigationMenu::class, 'parent_id');
    }
}
