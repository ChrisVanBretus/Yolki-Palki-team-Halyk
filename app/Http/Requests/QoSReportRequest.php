<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QoSReportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'qos_id' => 'required|integer|exists:qos_reports,id',
            'profile_id' => 'required|integer|exists:esim_profiles,id',
            'operator' => 'required|string|in:Op1,Op2,Op3',
            'signal' => 'required|integer',
            'network_type' => 'required|string|in:2G,3G,4G,5G',
            'download_speed' => 'required|numeric|min:0',
            'upload_speed' => 'required|numeric|min:0',
            'latency' => 'required|integer|min:0',
            'data_used' => 'required|integer|min:0',
            'minutes_used' => 'required|integer|min:0',
        ];
    }
}
