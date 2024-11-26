<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateOrderRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|max:255',
            'appartment' => 'required|string|max:255',
            'payment_method' => [
                'required',
                'string'
            ],
            'order_note' => 'nullable|string|max:500',
            'discount_code' => 'nullable|string|max:50',
        ];
    }
}
