<?php

declare(strict_types=1);

namespace App;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

interface HttpAppInterface extends AppInterface
{
    public function createRequestFromGlobals(): ServerRequestInterface;

    public function handle(ServerRequestInterface $request): void;

    public function getResponse(ServerRequestInterface $request): ResponseInterface;
}
