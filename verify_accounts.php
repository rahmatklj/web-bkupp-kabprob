<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

// Super Admin
$admin = User::where('id', 1)->first() ?: new User();
$admin->name = 'Administrator DKUPP';
$admin->email = 'admin@dkupp.probolinggokab.go.id';
$admin->username = 'admin';
$admin->password = Hash::make('admin123');
$admin->role = 'super_admin';
$admin->save();

// Staf
$staf = User::where('id', 2)->first() ?: new User();
$staf->name = 'Staf Pelayanan DKUPP';
$staf->email = 'staf@dkupp.probolinggokab.go.id';
$staf->username = 'staf';
$staf->password = Hash::make('staf123');
$staf->role = 'anggota';
$staf->save();

echo "User accounts verified successfully!\n";
