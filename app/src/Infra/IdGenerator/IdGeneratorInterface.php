<?php

declare(strict_types=1);

namespace App\Infra\IdGenerator;

use Ramsey\Uuid\UuidInterface;

interface IdGeneratorInterface
{
    public function generate(): UuidInterface;
}
