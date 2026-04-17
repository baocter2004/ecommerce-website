<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'shipping_address' => 'required|string|max:255',
            'appartment' => 'nullable|string|max:255',
            'payment_method' => [
                'required',
                'string'
            ],
            'order_note' => 'nullable|string|max:500',
        ];
    }

    public function attributes(): array
    {
        return [
            'shipping_address' => 'địa chỉ giao hàng',
            'appartment' => 'căn hộ/số nhà',
            'payment_method' => 'phương thức thanh toán',
            'order_note' => 'ghi chú đơn hàng',
            'discount_code' => 'mã giảm giá',
        ];
    }
}
