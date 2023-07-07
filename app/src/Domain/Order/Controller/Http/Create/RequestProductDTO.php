<?php

declare(strict_types=1);

namespace App\Domain\Order\Controller\Http\Create;

use App\Domain\Exception\ValidationException;

final readonly class RequestProductDTO
{
    public function __construct(
        public string $id,
        public int $count,
    ) {
    }

    /**
     * @param array<string, string> $item
     * @return static
     */
    public static function fromArray(array $item): self
    {
        return new self(
            id: $item['id']
                ?? throw new ValidationException(
                    "A product object needs the 'id' field"
                ),
            count: (int) ($item['count']
                ?? throw new ValidationException(
                    "A product object needs the 'count' field"
                )),
        );
    }
}
