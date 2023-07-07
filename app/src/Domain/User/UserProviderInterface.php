<?php

declare(strict_types=1);

namespace App\Domain\User;

use Ramsey\Uuid\UuidInterface;

interface UserProviderInterface
{
    public function getCurrentUserId(): UuidInterface;
}
