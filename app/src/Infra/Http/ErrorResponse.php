<?php

declare(strict_types=1);

namespace App\Infra\Http;

final readonly class ErrorResponse implements StatusCodeProviderInterface
{
    public const METHOD_NOT_ALLOWED = 405;
    public const RESOURCE_NOT_FOUND = 404;
    public const BAD_REQUEST = 400;
    public const INTERNAL = 500;

    public function __construct(
        public int $code,
        public string $message,
    ) {
    }

    public function getStatusCode(): int
    {
        return $this->code;
    }
}
