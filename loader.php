<?php

declare(strict_types=1);

$rootPath = defined('QUICK_STATIC_ROOT') ? QUICK_STATIC_ROOT : __DIR__;
$configFile = $rootPath.'/../bootstrap/cache/quick-static.php';
if (! is_file($configFile)) {
    return;
}

$config = require $configFile;

if (! ($config['debug'] ?? false) && ! is_array($config)) {
    return;
}

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
if (! in_array($method, ['GET', 'HEAD'], true)) {
    if ($config['send_headers'] ?? true) {
        header('X-Static-Cache: MISS');
    }

    return;
}

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = $config['cache_folder'].DIRECTORY_SEPARATOR.sha1($requestUri);

foreach (['html', 'json', 'xml'] as $ext) {
    $file = "$path.$ext";
    clearstatcache(true, $file);
    if (! is_file($file)) {
        continue;
    }

    $contentType = [
        'html' => 'text/html; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'xml' => 'application/xml; charset=utf-8',
    ][$ext];

    if ($config['send_headers'] ?? true) {
        header('X-Static-Cache: HIT');
    }
    header("Content-Type: $contentType");

    if ($method === 'HEAD') {
        header('Content-Length: '.filesize($file));
        exit;
    }

    $content = @file_get_contents($file);
    if ($content === false) {
        if ($config['send_headers'] ?? true) {
            header('X-Static-Cache: MISS');
        }
        break;
    }

    echo $content;
    exit;
}

if ($config['send_headers'] ?? true) {
    header('X-Static-Cache: MISS');
}
