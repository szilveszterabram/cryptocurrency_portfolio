<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserBalanceRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'balance' => 'required|numeric|gt:0',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
