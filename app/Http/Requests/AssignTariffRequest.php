<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignTariffRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'subscriber_id' => 'required|integer|exists:subscribers,id',
            // название мы не придумали)))
            'tariff_name' => 'required|string|in:Tar1,Tar2,Tar3',
        ];
    }
}
