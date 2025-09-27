<?php

namespace App\Services;

use App\Repositories\EsimRepository;
use App\DTO\EsimProfileDTO;
use App\Models\Subscriber;

class EsimService
{
    public function __construct(private EsimRepository $repo){}

    public function createProfile(Subscriber $subscriber, string $operator): EsimProfileDTO
    {
        $activationCode = 'ACT' . rand(1000, 9999);
        $profile = $this->repo->create($subscriber->id, $operator, $activationCode);
        return new EsimProfileDTO($profile->id, $profile->subscriber_id, $profile->operator, $profile->status, $profile->activation_code);
    }

    public function suspendProfile(int $profileId): EsimProfileDTO
    {
        $profile = $this->repo->suspend($this->repo->findById($profileId));
        return new EsimProfileDTO($profile->id, $profile->subscriber_id, $profile->operator, $profile->status, $profile->activation_code);
    }
}
