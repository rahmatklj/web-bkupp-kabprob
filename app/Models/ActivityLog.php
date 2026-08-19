<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'user_name',
        'user_role',
        'action',
        'module',
        'description',
        'ip_address',
    ];

    public static function record($action, $module, $description)
    {
        try {
            $user = Auth::user();
            return self::create([
                'user_id' => $user ? $user->id : null,
                'user_name' => $user ? $user->name : 'Sistem / Pengunjung',
                'user_role' => $user ? ($user->role === 'super_admin' ? 'Super Admin' : 'Anggota Staf') : 'Guest',
                'action' => strtoupper($action),
                'module' => $module,
                'description' => $description,
                'ip_address' => request()->ip(),
            ]);
        } catch (\Exception $e) {
            // Log fallback
        }
    }
}
