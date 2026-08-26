<?php

$html = file_get_contents('https://flareapp.io/share/Bm09AL0P');
$decoded = html_entity_decode($html);

// Find exception or error strings in the page
preg_match_all('/(Error Details|Class|SQLSTATE|Exception|Error|undefined|failed|PublicController|AdminController|AuthController)[^<>{}\"]*/i', $decoded, $matches);

echo "=== FLARE 2 ERROR MATCHES ===\n";
foreach (array_unique($matches[0]) as $m) {
    if (strlen(trim($m)) > 5) {
        echo "- " . trim($m) . "\n";
    }
}
