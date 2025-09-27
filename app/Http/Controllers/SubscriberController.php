<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\BillingService;
use App\Http\Requests\AssignTariffRequest;

class SubscriberController extends Controller
{
    public function __construct(private BillingService $billingService) {}

    // Создание нового абонента
    public function create(Request $request)
    {
        $user = $request->user();
        $subscriber = $this->billingService->createSubscriber($user);

        return response()->json($subscriber);
    }

    // Назначение тарифа абоненту
    public function assignTariff(AssignTariffRequest $request, $id)
    {
        $tariffDTO = $this->billingService->assignTariffById($id, $request->tariff_name);

        return response()->json($tariffDTO);
    }
}
