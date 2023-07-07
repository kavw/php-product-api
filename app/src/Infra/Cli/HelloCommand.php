<?php

declare(strict_types=1);

namespace App\Infra\Cli;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class HelloCommand extends Command
{
    public function __construct()
    {
        parent::__construct('hello');
    }

    public function getDescription(): string
    {
        return "Print a hello message";
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Hello!");
        return self::SUCCESS;
    }
}
