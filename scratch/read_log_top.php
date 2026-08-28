<?php

$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logPath)) {
    $content = file_get_contents($logPath);
    preg_match_all('/\[2026-[^\]]+\][^\n]+/', $content, $matches);
    echo "=== RECENT LOG ERROR HEADERS ===\n";
    $recent = array_slice($matches[0], -10);
    foreach ($recent as $r) {
        echo $r . "\n";
    }
}
