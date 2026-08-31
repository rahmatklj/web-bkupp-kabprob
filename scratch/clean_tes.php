<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

App\Models\NavigationMenu::where('title', 'LIKE', '%tes%')->delete();
echo "Deleted all 'tes' submenus.\n";
