<?php

declare(strict_types=1);

namespace App\Infra\Http\Client;

use GuzzleHttp\Client;
use Psr\Http\Client\ClientInterface;

class ClientFactory implements ClientFactoryInterface
{
    public function create(): ClientInterface
    {
        return new Client();
    }
}
