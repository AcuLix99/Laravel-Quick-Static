<?php

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $path = '_quick-static'.DIRECTORY_SEPARATOR.sha1(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
    $file = realpath("{$path}.html");
    if ($file) {
        header('X-Static-Cache: HIT');
        header('Content-Type: text/html; charset=utf-8');
        require $file;
        exit();
    }

    $file = realpath("{$path}.json");
    if ($file) {
        header('X-Static-Cache: HIT');
        header('Content-Type: application/json; charset=utf-8');
        require $file;
        exit();
    }

    $file = realpath("{$path}.xml");
    if ($file) {
        header('X-Static-Cache: HIT');
        header('Content-Type: application/xml; charset=utf-8');
        require $file;
        exit();
    }
}
