<?php

return [
    'cache_folder' => base_path('_quick-static'),
    'minify_html' => true,
    'send_headers' => true,
    'log' => true,
    'debug' => env('APP_DEBUG', false),
];
