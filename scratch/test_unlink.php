<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$dummyPath = public_path('uploads/news/test_unlink_dummy.jpg');
file_put_contents($dummyPath, 'test content');
echo 'Exists before delete: ' . (file_exists($dummyPath) ? 'YES' : 'NO') . PHP_EOL;

$news = \App\Models\NewsItem::create([
    'title' => 'Test Dummy Unlink',
    'slug' => 'test-dummy-unlink-' . time(),
    'summary' => 'Test summary',
    'content' => 'Test content',
    'image_url' => '/uploads/news/test_unlink_dummy.jpg',
    'category' => 'Berita Utama',
    'published_at' => now(),
]);

$news->delete();
echo 'Exists after delete: ' . (file_exists($dummyPath) ? 'YES' : 'NO') . PHP_EOL;
