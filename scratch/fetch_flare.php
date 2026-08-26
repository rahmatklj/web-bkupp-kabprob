<?php

$html = file_get_contents('https://flareapp.io/share/B5ZQYl27');

// Look for data-page attribute (Inertia.js apps embed full page data in data-page attribute!)
if (preg_match('/data-page="([^"]+)"/', $html, $matches)) {
    $json = html_entity_decode($matches[1]);
    $data = json_decode($json, true);
    file_put_contents(__DIR__ . '/flare_parsed.json', json_encode($data, JSON_PRETTY_PRINT));
    echo "INERTIA DATA FOUND & SAVED TO flare_parsed.json!\n";
} else {
    echo "NO DATA-PAGE FOUND. Searching for json scripts...\n";
    preg_match_all('/<script[^>]*>(.*?)<\/script>/s', $html, $scripts);
    foreach ($scripts[1] as $idx => $s) {
        if (strpos($s, 'SQL') !== false || strpos($s, 'Exception') !== false || strpos($s, 'props') !== false) {
            echo "Script {$idx} matches keywords!\n";
            file_put_contents(__DIR__ . "/flare_script_{$idx}.txt", $s);
        }
    }
}
