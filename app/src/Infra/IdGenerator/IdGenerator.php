<?php

declare(strict_types=1);

namespace App\Infra\IdGenerator;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class IdGenerator implements IdGeneratorInterface
{
    public function generate(): UuidInterface
    {
        return Uuid::uuid7();
    }
}
