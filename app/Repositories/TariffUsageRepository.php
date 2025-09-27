<?php

namespace App\Repositories;

use App\Models\TariffUsage;

class TariffUsageRepository
{
    // Создает тариф для абонента
    public function create($subscriberId, $tariffName): TariffUsage
    {
        return TariffUsage::create([
            'subscriber_id' => $subscriberId,
            'tariff_name' => $tariffName,
            'data_used' => 0,
            'minutes_used' => 0,
            'status' => 'active'
        ]);
    }

    // Обновляет использование тарифа (трафик и минуты)
    public function updateUsage(TariffUsage $tariff, $dataUsed, $minutesUsed): TariffUsage
    {
        $tariff->data_used += $dataUsed;
        $tariff->minutes_used += $minutesUsed;
        $tariff->save();
        return $tariff;
    }

    // Получает последний активный тариф абонента
    public function getLatestBySubscriber($subscriberId): ?TariffUsage
    {
        return TariffUsage::where('subscriber_id', $subscriberId)->latest()->first();
    }
}
