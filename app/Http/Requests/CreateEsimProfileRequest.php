<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateEsimProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'operator_id' => 'required|integer|exists:operators,id',
            // Партнеры-операторы
            'operator' => 'required|string|in:Op1,Op2,Op3',
        ];
    }
}
