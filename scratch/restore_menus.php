<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// 1. Restore Menu ID 14 (DOKUMEN)
$dokumen = App\Models\NavigationMenu::find(14);
if ($dokumen) {
    $dokumen->update([
        'title' => 'DOKUMEN',
        'parent_id' => null,
        'order' => 4,
        'url' => '#',
        'is_active' => true
    ]);
    echo "Restored ID 14 to DOKUMEN (order 4, parent NULL)\n";
} else {
    $dokumen = App\Models\NavigationMenu::create([
        'id' => 14,
        'title' => 'DOKUMEN',
        'parent_id' => null,
        'order' => 4,
        'url' => '#',
        'is_active' => true
    ]);
    echo "Created DOKUMEN menu (order 4)\n";
}

// 2. Delete test dummy menu items (IDs 29 to 43)
$deleted = App\Models\NavigationMenu::whereIn('id', range(29, 43))->delete();
echo "Deleted {$deleted} test dummy submenus\n";

// 3. Ensure menu orders are sequence 1 to 7
$orderMap = [
    'HOME' => 1,
    'PROFIL' => 2,
    'LAYANAN' => 3,
    'DOKUMEN' => 4,
    'INFORMASI' => 5,
    'HUBUNGI' => 6,
    'LOGIN' => 7,
];

foreach ($orderMap as $title => $ord) {
    App\Models\NavigationMenu::whereNull('parent_id')
        ->where('title', 'LIKE', $title)
        ->update(['order' => $ord]);
}

echo "Navigation Menu Database restoration complete!\n";
