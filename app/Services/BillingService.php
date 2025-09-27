<?php

namespace App\Services;

use App\Repositories\SubscriberRepository;
use App\Repositories\TariffUsageRepository;
use App\Models\Subscriber;
use App\DTO\TariffDTO;

class BillingService
{
    public function __construct(
        private SubscriberRepository $subscriberRepo,
        private TariffUsageRepository $tariffRepo
    ) {}

    /**
     * Создаёт нового абонента для пользователя
     */
    public function createSubscriber($user): Subscriber
    {
        return $this->subscriberRepo->create([
            'user_id' => $user->id,
            'current_operator' => null
        ]);
    }

    /**
     * Назначает тариф абоненту по ID
     */
    public function assignTariffById(int $subscriberId, string $tariffName): TariffDTO
    {
        $subscriber = $this->subscriberRepo->findById($subscriberId);

        return $this->assignTariff($subscriber, $tariffName);
    }

    /**
     * Назначает тариф абоненту
     */
    public function assignTariff(Subscriber $subscriber, string $tariffName): TariffDTO
    {
        $usage = $this->tariffRepo->assignTariff($subscriber, $tariffName);

        return new TariffDTO(
            subscriberId: $subscriber->id,
            tariffName: $usage->tariff_name,
            dataUsed: $usage->data_used,
            minutesUsed: $usage->minutes_used,
            status: $usage->status
        );
    }
}
