<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class OrgMember extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = [
        'name',
        'position',
        'type',
        'parent_id',
        'photo',
        'order',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
    ];

    protected static function booted()
    {
        static::deleting(function ($member) {
            self::deleteUploadFile($member->photo);
        });
    }

    public function parent()
    {
        return $this->belongsTo(OrgMember::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(OrgMember::class, 'parent_id')->orderBy('order', 'asc');
    }
}

