<?php

declare(strict_types=1);

namespace App\Domain\Event\Service;

use App\Domain\Event\Factory\EventFactory;
use Doctrine\ORM\EntityManagerInterface;

final class EventCollector implements EventCollectorInterface
{
    /** @var object[]  */
    private array $events = [];

    public function __construct(
        readonly private EventFactory $eventFactory,
        readonly private EntityManagerInterface $entityManager,
    ) {
    }

    public function raise(object $event): void
    {
        $this->events[] = $event;
    }

    public function flush(): void
    {
        foreach ($this->events as $event) {
            $entity = $this->eventFactory->create($event);
            $this->entityManager->persist($entity);
        }
        $this->events = [];
    }
}
