<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;


class AddMoneyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:50000' // Maximum amount limit
            ],
            'description' => [
                'nullable',
                'string',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'amount.min' => 'Minimum amount is ₹1',
            'amount.max' => 'Maximum amount is ₹50,000',
            'description.max' => 'Description cannot exceed 255 characters'
        ];
    }
}