<?php

declare(strict_types=1);

if (! in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD'], true)) {
    header('X-Static-Cache: MISS');

    return;
}

$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = '_quick-static'.DIRECTORY_SEPARATOR.sha1($requestUri);

foreach (['html', 'json', 'xml'] as $ext) {
    $file = realpath("$path.$ext");
    if (! $file) {
        continue;
    }

    $contentType = [
        'html' => 'text/html; charset=utf-8',
        'json' => 'application/json; charset=utf-8',
        'xml' => 'application/xml; charset=utf-8',
    ][$ext];

    header('X-Static-Cache: HIT');
    header("Content-Type: $contentType");

    if ($_SERVER['REQUEST_METHOD'] === 'HEAD') {
        exit;
    }

    require $file;
    exit;
}

header('X-Static-Cache: MISS');
