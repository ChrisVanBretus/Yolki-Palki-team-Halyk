<?php

namespace App\DTO;

class TariffUsageDTO
{
    public function __construct(
        public int $id,
        public int $subscriberId,
        public string $tariffName,
        public int $dataUsed,
        public int $minutesUsed,
        public string $status
    ) {}
}
