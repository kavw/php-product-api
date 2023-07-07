<?php

declare(strict_types=1);

namespace App\Domain\Event\Factory;

use App\Domain\Event\Entity\Event;
use App\Infra\IdGenerator\IdGeneratorInterface;
use Psr\Clock\ClockInterface;

final readonly class EventFactory
{
    public function __construct(
        private IdGeneratorInterface $idGenerator,
        private ClockInterface $clock,
    ) {
    }

    public function create(object $obj): Event
    {
        return new Event(
            $this->idGenerator->generate(),
            $this->clock->now(),
            get_class($obj),
            $obj,
        );
    }
}
