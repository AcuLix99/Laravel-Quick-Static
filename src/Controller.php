<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic;

use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class Controller
{
    protected ?Container $container = null;

    public const FOLDER_NAME = '_quick-static';

    public function setContainer($container): static
    {
        $this->container = $container;

        return $this;
    }

    public function store(Response $res): void
    {
        $file = sha1($_SERVER['REQUEST_URI']);
        $ext = '.'.$this->getExtension($res);
        file_put_contents($this->getPath().DIRECTORY_SEPARATOR.$file.$ext, $res->getContent());
    }

    public function getPath(): string
    {
        $path = $this->container->make('path.public').DIRECTORY_SEPARATOR.self::FOLDER_NAME;
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
}
