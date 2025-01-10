<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePriceObservationRequest extends FormRequest{
    public function rules(): array
    {
        return [
            'user_id' => ['required', 'exists:users'],
'asset_id' => ['required'],
'target' => ['nullable', 'numeric'],
'active' => ['nullable', 'boolean'],//
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
