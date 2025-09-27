<?php

namespace App\Services;

use App\Repositories\TariffUsageRepository;
use App\Models\Subscriber;
use App\DTO\TariffUsageDTO;

class BillingService
{
    public function __construct(private TariffUsageRepository $repo){}

    public function assignTariff(Subscriber $subscriber, string $tariffName): TariffUsageDTO
    {
        $tariff = $this->repo->create($subscriber->id, $tariffName);
        return new TariffUsageDTO($tariff->id, $tariff->subscriber_id, $tariff->tariff_name, $tariff->data_used, $tariff->minutes_used, $tariff->status);
    }

    public function updateUsage(Subscriber $subscriber, int $dataUsed, int $minutesUsed): TariffUsageDTO
    {
        $tariff = $this->repo->getLatestBySubscriber($subscriber->id);
        $tariff = $this->repo->updateUsage($tariff, $dataUsed, $minutesUsed);
        return new TariffUsageDTO($tariff->id, $tariff->subscriber_id, $tariff->tariff_name, $tariff->data_used, $tariff->minutes_used, $tariff->status);
    }
}
