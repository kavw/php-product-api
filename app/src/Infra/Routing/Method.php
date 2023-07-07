<?php

declare(strict_types=1);

namespace App\Infra\Routing;

enum Method: string
{
    case HEAD = 'HEAD';
    case GET = 'GET';
    case POST = 'POST';
    case PUT = 'PUT';
    case PATCH = 'PATCH';
    case DELETE = 'DELETE';

    public static function fromString(string $method): self
    {
        return match (strtoupper($method)) {
            self::HEAD->value => self::HEAD,
            self::GET->value => self::GET,
            self::POST->value => self::POST,
            self::PUT->value => self::PATCH,
            self::DELETE->value => self::DELETE,
            default => throw new \InvalidArgumentException(
                "Undefined method '{$method}'"
            )
        };
    }
}
