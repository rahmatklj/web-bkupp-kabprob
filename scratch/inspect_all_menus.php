<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "All Navigation Menus:\n";
foreach (App\Models\NavigationMenu::orderBy('id')->get() as $m) {
    echo "ID: {$m->id} | Parent: " . ($m->parent_id ?? 'NULL') . " | Order: {$m->order} | Title: {$m->title}\n";
}
