<?php

// Create a high-quality SVG QR Code for DKUPP Probolinggo
$svg = '<?xml version="1.0" encoding="UTF-8"?>
<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 33 33" shape-rendering="crispEdges">
    <rect width="33" height="33" fill="#ffffff"/>
    <g fill="#0f172a">
        <!-- Position Finder 1 (Top Left) -->
        <path d="M2,2 h7 v7 h-7 z M3,3 v5 h5 v-5 z M4,4 h3 v3 h-3 z"/>
        
        <!-- Position Finder 2 (Top Right) -->
        <path d="M24,2 h7 v7 h-7 z M25,3 v5 h5 v-5 z M26,4 h3 v3 h-3 z"/>
        
        <!-- Position Finder 3 (Bottom Left) -->
        <path d="M2,24 h7 v7 h-7 z M3,25 v5 h5 v-5 z M4,26 h3 v3 h-3 z"/>

        <!-- Alignment Pattern -->
        <path d="M22,22 h5 v5 h-5 z M23,23 v3 h3 v-3 z M24,24 h1 v1 h-1 z"/>
        
        <!-- Timing Patterns -->
        <path d="M6,10 h1 v1 h-1 z M6,12 h1 v1 h-1 z M6,14 h1 v1 h-1 z M6,16 h1 v1 h-1 z M6,18 h1 v1 h-1 z M6,20 h1 v1 h-1 z"/>
        <path d="M10,6 h1 v1 h-1 z M12,6 h1 v1 h-1 z M14,6 h1 v1 h-1 z M16,6 h1 v1 h-1 z M18,6 h1 v1 h-1 z M20,6 h1 v1 h-1 z"/>

        <!-- Data Modules Grid -->
        <path d="M10,2 h2 v1 h-2 z M13,2 h1 v2 h-1 z M15,2 h3 v1 h-3 z M19,2 h2 v2 h-2 z M22,2 h1 v1 h-1 z"/>
        <path d="M10,4 h1 v1 h-1 z M12,4 h3 v1 h-3 z M16,4 h1 v2 h-1 z M18,4 h2 v1 h-2 z M21,4 h2 v1 h-2 z"/>
        <path d="M10,9 h3 v1 h-3 z M14,9 h2 v1 h-2 z M17,9 h1 v2 h-1 z M19,9 h3 v1 h-3 z"/>
        <path d="M2,10 h2 v2 h-2 z M5,10 h1 v1 h-1 z M8,10 h1 v2 h-1 z"/>
        <path d="M10,11 h1 v3 h-1 z M12,11 h2 v1 h-2 z M15,11 h1 v1 h-1 z M18,11 h2 v2 h-2 z M21,11 h1 v1 h-1 z"/>
        <path d="M2,13 h1 v2 h-1 z M4,13 h2 v1 h-2 z M7,13 h2 v1 h-2 z M24,13 h3 v1 h-3 z M28,13 h3 v2 h-3 z"/>
        <path d="M10,15 h3 v1 h-3 z M14,15 h1 v2 h-1 z M16,15 h2 v1 h-2 z M19,15 h1 v1 h-1 z M21,15 h2 v2 h-2 z"/>
        <path d="M2,16 h3 v1 h-3 z M6,16 h1 v2 h-1 z M8,16 h1 v1 h-1 z M24,16 h1 v2 h-1 z M26,16 h2 v1 h-2 z M29,16 h2 v1 h-2 z"/>
        <path d="M10,17 h1 v1 h-1 z M12,17 h1 v2 h-1 z M15,17 h3 v1 h-3 z M19,17 h1 v2 h-1 z M28,17 h3 v1 h-3 z"/>
        <path d="M2,18 h2 v1 h-2 z M5,18 h1 v1 h-1 z M7,18 h2 v2 h-2 z M22,18 h2 v1 h-2 z M25,18 h2 v2 h-2 z M28,18 h1 v1 h-1 z"/>
        <path d="M10,19 h2 v2 h-2 z M13,19 h1 v1 h-1 z M15,19 h1 v1 h-1 z M17,19 h2 v1 h-2 z M20,19 h2 v1 h-2 z M30,19 h1 v2 h-1 z"/>
        <path d="M2,20 h1 v1 h-1 z M4,20 h2 v2 h-2 z M14,20 h2 v1 h-2 z M17,20 h1 v2 h-1 z M19,20 h2 v1 h-2 z M28,20 h1 v1 h-1 z"/>
        <path d="M10,22 h1 v1 h-1 z M12,22 h3 v1 h-3 z M16,22 h1 v1 h-1 z M18,22 h2 v2 h-2 z M28,22 h3 v1 h-3 z"/>
        <path d="M10,24 h3 v1 h-3 z M14,24 h1 v2 h-1 z M16,24 h2 v1 h-2 z M19,24 h2 v1 h-2 z M28,24 h1 v2 h-1 z M30,24 h1 v1 h-1 z"/>
        <path d="M10,26 h1 v2 h-1 z M12,26 h2 v1 h-2 z M15,26 h3 v1 h-3 z M19,26 h1 v1 h-1 z M21,26 h2 v2 h-2 z M29,26 h2 v1 h-2 z"/>
        <path d="M10,28 h2 v1 h-2 z M13,28 h2 v2 h-2 z M16,28 h1 v1 h-1 z M18,28 h3 v1 h-3 z M22,28 h2 v1 h-2 z M25,28 h3 v1 h-3 z M29,28 h2 v2 h-2 z"/>
        <path d="M10,30 h3 v1 h-3 z M15,30 h1 v1 h-1 z M17,30 h2 v1 h-2 z M20,30 h1 v1 h-1 z M22,30 h3 v1 h-3 z M26,30 h2 v1 h-2 z"/>
    </g>
</svg>';

$dir = __DIR__ . '/../public/uploads/settings';
if (!is_dir($dir)) {
    mkdir($dir, 0755, true);
}

file_put_contents($dir . '/qr_code_dkupp.svg', $svg);
echo "SVG QR Code created successfully at: " . $dir . '/qr_code_dkupp.svg';
