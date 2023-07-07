<?php

declare(strict_types=1);

namespace App;

use App\Infra\Cli\GuestCommandInterface;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;

final class CliApp extends AbstractApp implements CliAppInterface
{
    public function __construct(
        /**
         * @var Command[]
         */
        readonly private iterable $commands,
        /**
         * @var GuestCommandInterface[]
         */
        readonly private iterable $guestCommands,
    ) {
    }

    public function run(): void
    {
        $app = new Application('The Products API CLI tool', '0.0.1');
        foreach ($this->commands as $command) {
            $app->add($command);
        }
        foreach ($this->guestCommands as $command) {
            $command->addCommands($app);
        }
        $app->run();
    }
}
