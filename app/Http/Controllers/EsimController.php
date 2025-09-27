<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateEsimProfileRequest;

class EsimController extends Controller
{
    public function __construct(private CreateEsimProfileRequest $esimService) {}

    // Запрос на создание eSIM
    public function requestProfile(CreateEsimProfileRequest $request)
    {
        $subscriber = $request->user()->subscriber; // Получаем текущего абонента
        $operator = $request->input('operator');

        $profileDTO = $this->esimService->createProfile($subscriber, $operator);

        return response()->json($profileDTO);
    }

    // Список профилей eSIM у абонента
    public function listProfiles($subscriberId)
    {
        $profiles = $this->esimService->listProfiles($subscriberId);

        return response()->json($profiles);
    }

    // Заморозка eSIM профиля
    public function suspendProfile($profileId)
    {
        $profileDTO = $this->esimService->suspendProfile($profileId);

        return response()->json($profileDTO);
    }
}
