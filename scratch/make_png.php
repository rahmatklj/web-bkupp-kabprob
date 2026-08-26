<?php
$width = 320;
$height = 85;
$img = imagecreatetruecolor($width, $height);
imagesavealpha($img, true);
$transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
imagefill($img, 0, 0, $transparent);

$crimson = imagecolorallocate($img, 204, 9, 47);
$maroon = imagecolorallocate($img, 154, 0, 38);

// Text BerAKHLAK
imagestring($img, 5, 10, 15, 'BerAKHLAK', $crimson);

// Arrow chevron symbol
imagefilledpolygon($img, [160, 15, 172, 10, 178, 18, 172, 26, 160, 26, 168, 18], $crimson);

// Subtitle
imagestring($img, 2, 10, 48, 'Berorientasi Pelayanan Akuntabel Kompeten', $crimson);
imagestring($img, 2, 10, 62, 'Harmonis Loyal Adaptif Kolaboratif', $maroon);

imagepng($img, __DIR__ . '/../public/uploads/settings/logo_berakhlak.png');
imagedestroy($img);
echo "PNG GENERATED: " . filesize(__DIR__ . '/../public/uploads/settings/logo_berakhlak.png') . " bytes\n";
