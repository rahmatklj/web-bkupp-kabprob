<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $count = \App\Models\Gallery::count();
    $videos = \App\Models\Gallery::where('type', 'video')->where(function($q) { $q->where('is_active', true)->orWhereNull('is_active'); })->get();
    echo "SUCCESS! Total gallery items in DB: " . $count . "\n";
    echo "Total video items in DB: " . $videos->count() . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
