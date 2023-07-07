<?php

declare(strict_types=1);

namespace App\Domain\Order\Entity;

enum OrderStatusCode: string
{
    case CREATED = 'created';
    case PAID = 'paid';
}
