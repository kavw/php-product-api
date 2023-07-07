<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

interface PaymentGatewayClientInterface
{
    public function createPayment(string $paymentId): bool;

    public function ping(): int;
}
