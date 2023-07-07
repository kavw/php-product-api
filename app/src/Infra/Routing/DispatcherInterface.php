<?php

declare(strict_types=1);

namespace App\Infra\Routing;

use Psr\Http\Message\ServerRequestInterface;

interface DispatcherInterface
{
    public function dispatch(ServerRequestInterface $request): DispatchResult;
}
