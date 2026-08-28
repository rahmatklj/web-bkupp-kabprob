<?php

$ctx = stream_context_create(['http' => ['timeout' => 5]]);

$url1 = 'http://dkupp-probolinggo.test/admin/gallery';
$res1 = @file_get_contents($url1, false, $ctx);
echo "dkupp-probolinggo.test: " . ($res1 !== false ? "OK (" . strlen($res1) . " bytes)" : "FAILED: " . print_r(error_get_last(), true)) . "\n";

$url2 = 'http://127.0.0.1:8001/admin/gallery';
$res2 = @file_get_contents($url2, false, $ctx);
echo "127.0.0.1:8001: " . ($res2 !== false ? "OK (" . strlen($res2) . " bytes)" : "FAILED: " . print_r(error_get_last(), true)) . "\n";
