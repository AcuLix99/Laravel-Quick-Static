<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    protected ?Container $container = null;

    public function setContainer($container): static
    {
        $this->container = $container;

        return $this;
    }

    public function store(Response $res): void
    {
        if (config('quick-static.debug', false)) {
            return;
        }

        $file = sha1($_SERVER['REQUEST_URI']);
        $ext = '.'.$this->getExtension($res);
        $data = $res->getContent();
        if ($ext === '.html') {
            if (config('quick-static.minify_html', true)) {
                $data = $this->minifyHtml($data);
            }
        }
        if (! $data) {
            Log::warning('Failed to cache response', ['RequestUri' => $_SERVER['REQUEST_URI']]);

            return;
        }

        file_put_contents($this->getPath().DIRECTORY_SEPARATOR.$file.$ext, $data);
        if (config('quick-static.log', true)) {
            Log::info('Successfully cached response', [
                'RequestUri' => $_SERVER['REQUEST_URI'],
                'file' => $file.$ext,
            ]);
        }
    }

    public function getPath(): string
    {
        $path = config('quick-static.cache_folder');
        if (! is_dir($path)) {
            mkdir($path, 0775);
        }

        return $path;
    }

    public function getExtension(Response $res): string
    {
        $type = $res->headers->get('Content-Type');

        return match (true) {
            $res instanceof JsonResponse || $type === 'application/json' => 'json',
            in_array($type, ['text/xml', 'application/xml']) => 'xml',
            default => 'html',
        };
    }

    private function minifyHtml(string|false $html): string|false
    {
        if ($html === false) {
            return false;
        }

        return (new \voku\helper\HtmlMin)
            ->doRemoveComments(true)
            ->doRemoveWhitespaceAroundTags(true)
            ->doRemoveOmittedHtmlTags(false)
            ->doRemoveEmptyAttributes(true)
            ->doOptimizeAttributes(true)
            ->doRemoveHttpPrefixFromAttributes(false)
            ->doRemoveSpacesBetweenTags(true)
            ->minify($html);
    }
}
