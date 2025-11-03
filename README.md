- [Introduction](#introduction)
- [Installation](#installation)
  - [Install with Composer](#install-with-composer)
  - [.gitingore](#gitingore)
  - [URL Rewriting](#url-rewriting)
    - [Snippet](#snippet)
    - [Full file as reference](#full-file-as-reference)
- [Configuration](#configuration)
  - [Publish config file](#publish-config-file)
  - [Cache config file](#cache-config-file)
  - [Composer](#composer)
  - [Customizations](#customizations)
- [Usage](#usage)
  - [Using the middleware](#using-the-middleware)
  - [Clearing the cache](#clearing-the-cache)
  - [Optimization](#optimization)
- [License](#license)


\
[![Latest Version on Packagist](https://img.shields.io/packagist/v/aculix99/laravel-quick-static.svg?style=flat-square)](https://packagist.org/packages/aculix99/laravel-quick-static)
[![Total Downloads](https://img.shields.io/packagist/dt/aculix99/laravel-quick-static.svg?style=flat-square)](https://packagist.org/packages/aculix99/laravel-quick-static)

# Introduction
This package allows you to serve static html, json and xml files without booting laravel for best performance! It's inspired by [page-cache](https://github.com/JosephSilber/page-cache).

# Installation
## Install with Composer
```sh
composer require aculix99/laravel-quick-static
```
## .gitingore
- Add the cache folder name (default = `_quick-static`) to your `.gitingore` to avoid cached files in your vcs
## URL Rewriting
### Snippet
Add these lines to the `public/index.php` of your laravel application right after the use statements:
```php
define('QUICK_STATIC_ROOT', __DIR__);
require __DIR__ . '/../vendor/aculix99/laravel-quick-static/loader.php';
```
### Full file as reference
Your full index.php will then look like this:
```php
<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('QUICK_STATIC_ROOT', __DIR__);
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
# Configuration
## Publish config file
```sh
php artisan vendor:publish --tag=quick-static
```
## Cache config file
- The config file needs to be cached so that the loader.php can read it without booting laravel.
- Run the following command to generate `bootstrap/cache/quick-static.php`
```sh
php artisan quick-static:cache-config
```
## Composer
- Add these two commands to your `post-autoload-dump`-script
```json
"post-autoload-dump": [
    // "Illuminate\\Foundation\\ComposerScripts::postAutoloadDump",
    // "@php artisan package:discover --ansi",
    "@php artisan vendor:publish --tag=quick-static",
    "@php artisan quick-static:cache-config"
],
```
## Customizations
Feel free to use and adjust the loader within your `index.php` the way you like it. Since it's PHP rewriting, you can do your very own, performant **PHP MAGIC**. Also you can ignore cached files if query parameters are present.
# Usage
## Using the middleware
Just add the middleware to the corresponding routes like this:
```php
...
Route::middleware(\Aculix99\LaravelQuickStatic\Middleware\StoreStatic::class)->group(function() {
    Route::get('/path', fn() => view('test'));
    Route::get('/json', fn() => response()->json('test'));
});
...
```
## Clearing the cache
```sh
php artisan quick-static:clear
```
## Optimization
- The quick-static compiled config cache file will be automatically created and deleted when `php artisan optimize` and `php artisan optimize:clear` gets utilized.
- Furthermore the config file will be cached when the composer script `post-autoload-dump` runs, so after every
  - `composer dump-autoload`
  - `composer install`
  - `composer update`
- Also you can manually create and delete the compiled config file:
  - `php artisan quick-static:cache-config`
  - `php artisan quick-static:clear-cached-config`

# License
This package is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).