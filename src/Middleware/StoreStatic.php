<?php

declare(strict_types=1);

namespace Aculix99\LaravelQuickStatic\Middleware;

use Aculix99\LaravelQuickStatic\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreStatic
{
    public function __construct(protected Controller $controller) {}

    public function handle(Request $req, \Closure $next): Response
    {
        $res = $next($req);

        if ($req->isMethod('GET') && $res->getStatusCode() === 200) {
            $this->controller->store($res);
        }

        return $res;
    }
}
