<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller\Http\Create;

use App\Domain\Order\DTO\ResponseOrderDTO;
use App\Infra\Http\StatusCodeProviderInterface;

final readonly class Response implements StatusCodeProviderInterface
{
    public function __construct(
        public ResponseOrderDTO $order
    ) {
    }

    public function getStatusCode(): int
    {
        return 201;
    }
}
