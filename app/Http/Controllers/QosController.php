<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\QoSService;

class QosController extends Controller
{
    public function __construct(private QoSService $qosService) {}

    // Отчёт о качестве сети от приложения
    public function report(Request $request)
    {
        $subscriberId = $request->input('subscriber_id');
        $qosData = $request->only([
            'operator',
            'signal',
            'network_type',
            'download_speed',
            'upload_speed',
            'latency',
            'data_used',
            'minutes_used'
        ]);

        $bestOperator = $this->qosService->processQoS($subscriberId, $qosData);

        return response()->json([
            'best_operator' => $bestOperator
        ]);
    }
}
