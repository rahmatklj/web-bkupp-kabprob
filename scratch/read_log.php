<?php

$logPath = __DIR__ . '/../storage/logs/laravel.log';
if (file_exists($logPath)) {
    $content = file_get_contents($logPath);
    $lines = explode("\n", $content);
    $lastLines = array_slice($lines, -60);
    echo "=== LAST 60 LINES OF LARAVEL.LOG ===\n";
    echo implode("\n", $lastLines);
} else {
    echo "No laravel.log file found!";
}
