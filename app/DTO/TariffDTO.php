<?php

namespace App\DTO;

class TariffDTO
{
    public function __construct(
        public int $subscriberId,
        public string $tariffName,
        public int $dataUsed,
        public int $minutesUsed,
        public string $status
    ) {}
}
