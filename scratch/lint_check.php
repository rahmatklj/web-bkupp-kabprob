<?php

function getFiles($dir) {
    $results = [];
    $files = scandir($dir);
    foreach ($files as $f) {
        if ($f === '.' || $f === '..') continue;
        $path = $dir . '/' . $f;
        if (is_dir($path)) {
            $results = array_merge($results, getFiles($path));
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'php') {
            $results[] = $path;
        }
    }
    return $results;
}

$dirs = ['app', 'routes', 'config', 'database', 'resources/views'];
$errors = [];

foreach ($dirs as $d) {
    if (!is_dir($d)) continue;
    $files = getFiles($d);
    foreach ($files as $f) {
        $phpPath = 'C:\\laragon\\bin\\php\\php-8.3.30-Win32-vs16-x64\\php.exe';
        $cmd = '"' . $phpPath . '" -l "' . $f . '"';
        $output = shell_exec($cmd);
        if (strpos($output, 'No syntax errors') === false) {
            $errors[] = $f . ': ' . trim($output);
        }
    }
}

if (empty($errors)) {
    echo "ALL PHP AND BLADE FILES PASSED LINT WITH ZERO SYNTAX ERRORS!\n";
} else {
    echo "SYNTAX ERRORS FOUND:\n" . implode("\n", $errors) . "\n";
}
