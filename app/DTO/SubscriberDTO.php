<?php

namespace App\DTO;

class SubscriberDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public ?string $currentOperator
    ) {}
}
