<?php

declare(strict_types=1);

namespace App\Domain\Product\Controller\Http\List;

use App\Domain\Product\DTO\ResponseProductDTO;

final readonly class Response
{
    public function __construct(
        /**
         * @var ResponseProductDTO[]
         */
        public array $items = []
    ) {
    }
}
