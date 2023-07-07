<?php

declare(strict_types=1);

namespace App;

interface CliAppInterface extends AppInterface
{
    public function run(): void;
}
