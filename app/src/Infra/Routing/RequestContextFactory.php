<?php

declare(strict_types=1);

namespace App\Infra\Routing;

use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Routing\RequestContext;

final class RequestContextFactory
{
    public function createFromRequest(ServerRequestInterface $request): RequestContext
    {
        return new RequestContext(
            method: $request->getMethod(),
            host: $request->getUri()->getHost(),
            scheme: $request->getUri()->getScheme(),
            path: $request->getUri()->getPath(),
            queryString: $request->getUri()->getQuery(),
        );
    }
}
