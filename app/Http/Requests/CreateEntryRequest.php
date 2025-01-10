<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Validator;

class CreateEntryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'portfolio_id' => 'required',
            'asset_short' => 'required|string',
            'asset_long' => 'required|string',
            'amount' => 'required|numeric|gt:0',
            'price_at_buy' => 'required|numeric',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function after(): array
    {
        return [
            function (Validator $validator) {
                $user = auth()->user();
                Log::debug($this->input());
                if ($user->balance < $this->input('amount') * $this->input('price_at_buy')) {
                    $validator->errors()->add(
                        'amount',
                        'You do not have enough funds to buy this asset. Please lower the amount or add more funds to your account.');
                }
            }
        ];
    }
}
