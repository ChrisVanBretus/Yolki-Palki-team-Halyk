<?php

namespace App\Repositories;

use App\Models\EsimProfile;

class EsimRepository
{
    // Находит eSIM профиль по ID
    public function findById($id): ?EsimProfile
    {
        return EsimProfile::find($id);
    }

    // Создает новый eSIM профиль
    public function create($subscriberId, $operator, $activationCode): EsimProfile
    {
        return EsimProfile::create([
            'subscriber_id' => $subscriberId,
            'operator' => $operator,
            'activation_code' => $activationCode
        ]);
    }

    // Замораживает eSIM профиль
    public function suspend(EsimProfile $profile): EsimProfile
    {
        $profile->status = 'suspended';
        $profile->save();
        return $profile;
    }

    // Возвращает все eSIM профили абонента
    public function listBySubscriber($subscriberId)
    {
        return EsimProfile::where('subscriber_id', $subscriberId)->get();
    }
}
