<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DeletesUploadFiles;

class SiteSetting extends Model
{
    use HasFactory, DeletesUploadFiles;

    protected $fillable = ['key', 'value', 'label', 'type'];

    public static function get($key, $default = null)
    {
        $setting = self::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value)
    {
        $setting = self::where('key', $key)->first();
        if ($setting && $setting->value && $setting->value !== $value) {
            self::deleteUploadFile($setting->value);
        }
        return self::updateOrCreate(['key' => $key], ['value' => $value]);
    }
}

