<?php

declare(strict_types=1);

namespace App\Domain\Event\Service;

use App\Domain\EventDispatcherInterface;

interface EventCollectorInterface extends EventDispatcherInterface
{
    public function flush(): void;
}
