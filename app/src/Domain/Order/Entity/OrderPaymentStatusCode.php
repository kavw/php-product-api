<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

enum OrderPaymentStatusCode: string
{
    case CREATED = 'created';
    case DONE = 'done';
}
