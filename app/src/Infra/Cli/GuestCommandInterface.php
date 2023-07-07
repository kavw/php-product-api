<?php

declare(strict_types=1);

namespace App\Infra\Cli;

use Symfony\Component\Console\Application;

interface GuestCommandInterface
{
    public function addCommands(Application $application): void;
}
