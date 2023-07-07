<?php

declare(strict_types=1);

namespace App\Domain\Event\Factory;

use App\Domain\Event\Service\EventCollector;
use App\Domain\Event\Service\EventCollectorInterface;
use App\Domain\EventDispatcherInterface;
use Doctrine\ORM\EntityManagerInterface;

readonly class EventCollectorFactory
{
    public function __construct(
        private EventFactory $eventFactory,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function create(): EventCollectorInterface
    {
        return new EventCollector(
            $this->eventFactory,
            $this->entityManager,
        );
    }
}
