<?php

$data = json_decode(file_get_contents(__DIR__ . '/flare_parsed.json'), true);
echo "KEYS IN PARSED JSON:\n";
print_r(array_keys($data));
if (isset($data['props'])) {
    echo "PROPS KEYS:\n";
    print_r(array_keys($data['props']));
}
