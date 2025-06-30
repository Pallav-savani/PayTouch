<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CcBillPaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'cn' => 'required|string|max:20|min:13|regex:/^[0-9]+$/',
            'op' => 'required|string|max:50',
            'cir' => 'required|string|max:50',
            'amt' => 'required|numeric|min:1|max:50000',
            'ad9' => 'nullable|string|max:255',
            'ad3' => 'nullable|string|max:255',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'user_id.required' => 'User ID is required',
            'user_id.exists' => 'Selected user does not exist',
            'cn.required' => 'Credit card number is required',
            'cn.regex' => 'Credit card number must contain only digits',
            'cn.min' => 'Credit card number must be at least 13 digits',
            'cn.max' => 'Credit card number must not exceed 20 digits',
            'op.required' => 'Operator code is required',
            'cir.required' => 'Circle code is required',
            'amt.required' => 'Amount is required',
            'amt.min' => 'Amount must be at least ₹1',
            'amt.max' => 'Amount must not exceed ₹50,000',
        ];
    }
}