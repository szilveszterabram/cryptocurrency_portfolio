<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreatePriceObservationRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'asset_id' => ['required'],
            'target' => ['required', 'numeric', 'gt:0'],
            'active' => ['required', 'boolean'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
