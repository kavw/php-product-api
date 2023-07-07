<?php

declare(strict_types=1);

namespace App\Infra\Http;

use App\Defaults;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Symfony\Component\Serializer\SerializerInterface;

final readonly class ResponseFactory
{
    public function __construct(
        private SerializerInterface $serializer,
        private StreamFactoryInterface $streamFactory,
        private ResponseFactoryInterface $responseFactory,
    ) {
    }

    public function create(object $obj): ResponseInterface
    {
        $data = $this->serializer->serialize($obj, Defaults::SERIALIZATION_FORMAT);
        $stream = $this->streamFactory->createStream($data);
        $code = $obj instanceof StatusCodeProviderInterface
            ? $obj->getStatusCode()
            : 200;

        return $this->responseFactory
            ->createResponse($code)
            ->withBody($stream);
    }
}
