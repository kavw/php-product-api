<?php

declare(strict_types=1);

namespace App\Domain\Order\DTO;

final readonly class ResponseOrderPaymentDTO
{
    public function __construct(
        public string $id,
        public string $sum,
        public string $status,
        public ResponseOrderDTO $order,
    ) {
    }
}
