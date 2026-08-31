<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$profil = App\Models\NavigationMenu::whereNull('parent_id')->where('title', 'PROFIL')->first();

if ($profil) {
    $extraSubmenus = [
        'Sejarah & Latar Belakang',
        'Maklumat Pelayanan',
        'Struktur Sekretariat',
        'Bidang Koperasi & Usaha Mikro',
        'Bidang Perdagangan',
        'Bidang Perindustrian',
        'Peta Wilayah Kerja',
        'Penghargaan & Prestasi'
    ];

    $maxOrder = App\Models\NavigationMenu::where('parent_id', $profil->id)->max('order') ?? 0;

    foreach ($extraSubmenus as $index => $title) {
        $existing = App\Models\NavigationMenu::where('parent_id', $profil->id)->where('title', $title)->first();
        if (!$existing) {
            App\Models\NavigationMenu::create([
                'parent_id' => $profil->id,
                'title' => $title,
                'url' => '#',
                'order' => $maxOrder + $index + 1,
                'is_active' => true
            ]);
            echo "Added submenu: {$title}\n";
        }
    }
}
