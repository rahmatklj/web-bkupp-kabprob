<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\MasterCategory;

if (MasterCategory::count() === 0) {
    $layanan = [
        'USAHA MIKRO' => 'fa-store',
        'KOPERASI' => 'fa-users',
        'PERDAGANGAN' => 'fa-shopping-basket',
        'PERINDUSTRIAN' => 'fa-industry',
        'METROLOGI LEGAL' => 'fa-balance-scale',
        'PELAYANAN UMUM' => 'fa-handshake'
    ];
    foreach ($layanan as $name => $icon) {
        MasterCategory::create(['type' => 'layanan', 'name' => $name, 'icon' => $icon]);
    }

    $dokumen = ['Perencanaan Kinerja', 'Pengukuran Kinerja', 'Pelaporan Kinerja', 'Evaluasi Kinerja'];
    foreach ($dokumen as $name) {
        MasterCategory::create(['type' => 'dokumen', 'name' => $name, 'icon' => 'fa-file-pdf']);
    }

    $berita = ['Berita Utama', 'Pengumuman', 'Koperasi', 'Usaha Mikro & UMKM', 'Perdagangan', 'Perindustrian', 'Metrologi Legal'];
    foreach ($berita as $name) {
        MasterCategory::create(['type' => 'berita', 'name' => $name, 'icon' => 'fa-newspaper']);
    }
    
    echo "Master Categories Seeded Successfully!\n";
} else {
    echo "Master Categories already exist!\n";
}
