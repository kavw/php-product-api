<?php

declare(strict_types=1);

namespace App\Infra\Routing;

interface ActionInterface
{
    public function getRoute(): Route;
}
