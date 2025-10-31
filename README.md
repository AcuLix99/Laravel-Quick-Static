# Laravel Quick Static
- [Introduction](#introduction)
- [Installation](#installation)
  - [Composer](#composer)
  - [URL Rewriting](#url-rewriting)
    - [Snippet](#snippet)
    - [Full file as reference](#full-file-as-reference)
    - [Customizations](#customizations)
- [Usage](#usage)
  - [Using the middleware](#using-the-middleware)
  - [Clearing the cache](#clearing-the-cache)
- [License](#license)

\
[![Latest Version on Packagist](https://img.shields.io/packagist/v/aculix99/laravel-quick-static.svg?style=flat-square)](https://packagist.org/packages/aculix99/laravel-quick-static)
[![Total Downloads](https://img.shields.io/packagist/dt/aculix99/laravel-quick-static.svg?style=flat-square)](https://packagist.org/packages/aculix99/laravel-quick-static)

## Introduction
This package allows you to serve static html, json and xml files without booting laravel for best performance! It's inspired by [page-cache](https://github.com/JosephSilber/page-cache).

## Installation
### Composer
```sh
composer require aculix99/laravel-quick-static
```
### URL Rewriting
#### Snippet
Add this line to the `public/index.php` of your laravel application right after the use statements:
```php
require __DIR__ . '/../vendor/aculix99/laravel-quick-static/loader.php';
```
#### Full file as reference
Your full index.php will then look like this:
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

require __DIR__ . '/../vendor/aculix99/laravel-quick-static/loader.php';

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());

```
#### Customizations
Feel free to adjust the loader within your `index.php` the way you like it. Since it's PHP rewriting, you can do your very own, performant **PHP MAGIC**. Also you can ignore cached files if query parameters are present.
## Usage
### Using the middleware
Just add the middleware to the corresponding routes like this:
```php
...
Route::middleware(\Aculix99\LaravelQuickStatic\Middleware\StoreStatic::class)->group(function() {
    Route::get('/path', fn() => view('test'));
    Route::get('/json', fn() => response()->json('test'));
});
...
```
### Clearing the cache
```sh
php artisan quick-static:clear
```

## License
This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).