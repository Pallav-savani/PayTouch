<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TransferMoneyRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->wallet !== null;
    }

    public function rules(): array
    {
        $userWallet = Auth::user()->wallet;
        
        return [
            'to_wallet_id' => [
                'required',
                'string',
                'exists:wallets,wallet_id',
                $userWallet ? Rule::notIn([$userWallet->wallet_id]) : 'required' // Cannot transfer to self
            ],
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:25000' // Maximum transfer limit
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
            'to_wallet_id.required' => 'Recipient wallet ID is required',
            'to_wallet_id.exists' => 'Recipient wallet not found',
            'to_wallet_id.not_in' => 'Cannot transfer money to your own wallet',
            'amount.required' => 'Amount is required',
            'amount.numeric' => 'Amount must be a valid number',
            'amount.min' => 'Minimum transfer amount is ₹1',
            'amount.max' => 'Maximum transfer amount is ₹25,000',
            'description.max' => 'Description cannot exceed 255 characters'
        ];
    }
}