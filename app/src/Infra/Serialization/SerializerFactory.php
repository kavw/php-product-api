<?php

declare(strict_types=1);

namespace App\Infra\Serialization;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\JsonSerializableNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

final class SerializerFactory
{
    public function __invoke(): SerializerInterface
    {
        return new Serializer([
            new JsonSerializableNormalizer(),
            new ObjectNormalizer()
        ], [
            new JsonEncoder()
        ]);
    }
}
