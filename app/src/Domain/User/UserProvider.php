<?php

declare(strict_types=1);

namespace App\Domain\User;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

final class UserProvider implements UserProviderInterface
{
    public function getCurrentUserId(): UuidInterface
    {
        return Uuid::fromString('01891cb4-7598-71e7-8664-f91f0ece6704');
    }
}
