<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$profil = App\Models\NavigationMenu::whereNull('parent_id')->where('title', 'PROFIL')->first();

if ($profil) {
    // Keep only the original 4 submenus
    $originalTitles = [
        'Struktur Organisasi',
        'Visi Misi',
        'Tugas dan Fungsi',
        'Hasil SKM'
    ];

    // Delete any submenus that are not in the original 4 titles
    $deleted = App\Models\NavigationMenu::where('parent_id', $profil->id)
        ->whereNotIn('title', $originalTitles)
        ->delete();

    echo "Deleted {$deleted} extra submenus from PROFIL.\n";

    // Re-index original 4 items order from 1 to 4
    $order = 1;
    foreach ($originalTitles as $title) {
        $item = App\Models\NavigationMenu::where('parent_id', $profil->id)->where('title', $title)->first();
        if ($item) {
            $item->update(['order' => $order++]);
        }
    }
    echo "PROFIL submenus restored to original 4 items cleanly.\n";
}
