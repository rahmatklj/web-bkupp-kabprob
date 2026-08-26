<?php

$html = file_get_contents('https://flareapp.io/share/B5ZQYl27');

file_put_contents(__DIR__ . '/flare_raw.html', $html);

// Decode html entities if any
$decoded = html_entity_decode($html);

// Find text around Exception or QueryException or SQLSTATE or Error
preg_match_all('/(SQLSTATE|Exception|Error|galleries|PublicController|AdminController|Tabel)[^<>{}\"]*/i', $decoded, $matches);

echo "MATCHES FOUND IN FLARE HTML:\n";
foreach (array_unique($matches[0]) as $m) {
    if (strlen(trim($m)) > 5) {
        echo "- " . trim($m) . "\n";
    }
}
