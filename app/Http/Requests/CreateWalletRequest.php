<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Http\FormRequest;

class CreateWalletRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        return [
            'mobile' => [
                'required',
                'string',
                'regex:/^[6-9]\d{9}$/', // Indian mobile number format
                'unique:wallets,mobile'
            ],
            'email' => [
                'nullable',
                'email',
                'max:255'
            ]
        ];
    }

    public function messages(): array
    {
        return [
            'mobile.required' => 'Mobile number is required',
            'mobile.regex' => 'Please enter a valid Indian mobile number',
            'mobile.unique' => 'A wallet already exists with this mobile number',
            'email.email' => 'Please enter a valid email address'
        ];
    }
}