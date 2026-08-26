<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$users = User::all();

echo "=== DAFTAR AKUN ADMIN & STAFF SEEDER ===\n";
foreach ($users as $u) {
    echo "- Name: " . $u->name . "\n";
    echo "  Email: " . $u->email . "\n";
    echo "  Role: " . $u->role . "\n";
    echo "----------------------------------------\n";
}

// Reset password for super admin and staff to 'password' for convenience
$admin = User::where('email', 'admin@dkupp.probolinggokab.go.id')->first();
if ($admin) {
    $admin->password = Hash::make('password');
    $admin->save();
}

$staf = User::where('email', 'staf@dkupp.probolinggokab.go.id')->first();
if ($staf) {
    $staf->password = Hash::make('password');
    $staf->save();
}

echo "PASSWORDS RESET TO 'password' FOR BOTH ADMIN & STAF ACCOUNTS!\n";
