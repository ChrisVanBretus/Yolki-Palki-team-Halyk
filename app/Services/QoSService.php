<?php

namespace App\Services;

use App\Models\Subscriber;

class QoSService
{
    public function processQoS(Subscriber $subscriber, array $qosData): string
    {
        $bestOperator = $qosData['operator'];
        $subscriber->current_operator = $bestOperator;
        $subscriber->save();

        return $bestOperator;
    }
}
