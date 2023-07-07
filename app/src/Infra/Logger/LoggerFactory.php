<?php

declare(strict_types=1);

namespace App\Infra\Logger;

use Monolog\Handler\StreamHandler;
use Monolog\Level;
use Monolog\Logger;
use Psr\Log\LoggerInterface;

final class LoggerFactory
{
    private ?LoggerInterface $logger = null;

    public function __construct(
        readonly private string $loggerName,
        /**
         * @var resource|string
         */
        readonly private mixed $stream,
        readonly private Level $level = Level::Info,
    ) {
    }

    public function __invoke(): LoggerInterface
    {
        if ($this->logger !== null) {
            return $this->logger;
        }

        $logger = new Logger($this->loggerName);
        $logger->pushHandler(
            new StreamHandler($this->stream, $this->level)
        );

        return $this->logger = $logger;
    }
}
