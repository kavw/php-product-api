<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller\Http\Pay;

use App\Domain\Order\DTO\ResponseOrderDTO;
use App\Domain\Order\DTO\ResponseOrderPaymentDTO;
use App\Infra\Http\StatusCodeProviderInterface;

final readonly class Response implements StatusCodeProviderInterface
{
    public function __construct(
        public ResponseOrderPaymentDTO $payment
    ) {
    }

    public function getStatusCode(): int
    {
        return 201;
    }
}
