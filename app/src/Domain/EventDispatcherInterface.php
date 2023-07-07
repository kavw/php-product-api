<?php

declare(strict_types=1);

namespace App\Domain;

interface EventDispatcherInterface
{
    public function raise(object $event): void;
}
