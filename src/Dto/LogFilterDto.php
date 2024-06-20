<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class LogFilterDto
{
    public function __construct(
        #[Assert\All(
            [new Assert\Type('string')]
        )]
        public ?array $serviceNames = [],
        #[Assert\Type('integer')]
        public ?int $statusCode = null,
        #[Assert\Date]
        public ?string $startDate = null,
        #[Assert\Date]
        public ?string $endDate = null,
    ) {
    }
}
