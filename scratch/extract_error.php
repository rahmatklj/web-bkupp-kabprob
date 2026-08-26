<?php

$data = json_decode(file_get_contents(__DIR__ . '/flare_parsed.json'), true);

$report = $data['props']['report'] ?? $data['props']['sharedReport'] ?? $data['props']['occurrence'] ?? null;

echo "=== FLARE REPORT DETAILS ===\n";
echo "COMPONENT/PAGE: " . ($data['component'] ?? 'Unknown') . "\n";

function printRec($arr, $prefix = '') {
    foreach ($arr as $k => $v) {
        if (is_array($v)) {
            echo "{$prefix}{$k}:\n";
            printRec($v, $prefix . '  ');
        } else {
            if (in_array($k, ['class', 'exception_class', 'message', 'file', 'line_number', 'sql', 'url'])) {
                echo "{$prefix}**{$k}**: {$v}\n";
            }
        }
    }
}

if ($report) {
    printRec($report);
} else {
    echo "PROPS KEYS: " . implode(', ', array_keys($data['props'] ?? [])) . "\n";
    printRec($data['props']);
}
