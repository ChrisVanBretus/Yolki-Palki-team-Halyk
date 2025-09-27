<?php

namespace App\DTO;

class EsimProfileDTO
{
    public function __construct(
        public int $id,
        public int $subscriberId,
        public string $operator,
        public string $status,
        public ?string $activationCode
    ) {}
}
