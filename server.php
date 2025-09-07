<?php
// PHP Built-in server router for correct MIME types
$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$ext = pathinfo($path, PATHINFO_EXTENSION);

// Set correct MIME types
$mimeTypes = [
    'css' => 'text/css',
    'js' => 'application/javascript',
    'json' => 'application/json',
    'jpg' => 'image/jpeg',
    'jpeg' => 'image/jpeg',
    'png' => 'image/png',
    'gif' => 'image/gif',
    'svg' => 'image/svg+xml',
    'ico' => 'image/x-icon',
    'webp' => 'image/webp',
    'woff' => 'font/woff',
    'woff2' => 'font/woff2',
    'ttf' => 'font/ttf',
    'otf' => 'font/otf',
];

if (isset($mimeTypes[$ext])) {
    header('Content-Type: ' . $mimeTypes[$ext]);
}

// Check if file exists
$file = __DIR__ . $path;
if (file_exists($file) && is_file($file)) {
    return false; // Serve the requested file
}

// Default to index.php for routing
$_SERVER['SCRIPT_NAME'] = '/index.php';
require __DIR__ . '/index.php';