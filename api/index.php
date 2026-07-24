<?php 

// Setup folder temporary untuk Vercel Serverless (karena filesystem Vercel read-only kecuali /tmp)
$tmpDirs = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/cache',
    '/tmp/storage/bootstrap/cache',
    '/tmp/logs',
];

foreach ($tmpDirs as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0755, true);
    }
}

require __DIR__. '/../public/index.php';