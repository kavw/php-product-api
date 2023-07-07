<?php

declare(strict_types=1);

namespace App\Domain\Order\Service;

use App\Infra\Http\Client\ClientFactoryInterface;
use App\Infra\Http\Client\RequestFactoryInterface;
use Psr\Http\Client\ClientInterface;

final readonly class PaymentGatewayClientStub implements PaymentGatewayClientInterface
{
    public function __construct(
        private string $gatewayUrl,
        private ClientFactoryInterface $clientFactory,
        private RequestFactoryInterface $requestFactory,
    ) {
    }

    public function createPayment(string $paymentId): bool
    {
        $client = $this->clientFactory->create();
        $request = $this->requestFactory->create('GET', $this->gatewayUrl);
        $response = $client->sendRequest($request);
        return $response->getStatusCode() === 200;
    }

    public function ping(): int
    {
        $client = $this->clientFactory->create();
        $request = $this->requestFactory->create('GET', $this->gatewayUrl);
        $response = $client->sendRequest($request);
        return $response->getStatusCode();
    }
}
