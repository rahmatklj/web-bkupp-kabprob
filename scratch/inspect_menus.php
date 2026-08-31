<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$parents = App\Models\NavigationMenu::whereNull('parent_id')->orderBy('order', 'asc')->get();

echo "Top Level Menus:\n";
foreach ($parents as $m) {
    echo "ID: {$m->id} | Order: {$m->order} | Title: {$m->title} | Active: {$m->is_active}\n";
    $children = App\Models\NavigationMenu::where('parent_id', $m->id)->orderBy('order', 'asc')->get();
    foreach ($children as $c) {
        echo "   └─ Submenu ID: {$c->id} | Order: {$c->order} | Title: {$c->title}\n";
    }
}
