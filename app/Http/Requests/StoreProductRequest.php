<?php

namespace App\Http\Requests;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            "product_name" => 'required|max:255',
            "price" => 'required|numeric|min:0',
            "product_image" => 'required|image|max:2048',
            "description" => 'required',
            "short_description" => 'required',
            "quantity" => 'nullable|integer|min:0',
            "is_active" => [
                'nullable',
                Rule::in([0,1])
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
            ],
            'variant_name' => 'nullable|string|max:255',
            'variant_options' => 'nullable|array',
            'variant_options.*.option' => 'nullable|required_with:variant_name|string|max:255',
            'variant_options.*.price_modifier' => 'nullable|required_with:variant_name|numeric|min:0',
            'variant_options.*.quantity' => 'nullable|required_with:variant_name|integer|min:0',
        ];
    }

    public function attributes(): array
    {
        return [
            'product_name' => 'tên sản phẩm',
            'price' => 'giá sản phẩm',
            'product_image' => 'hình ảnh sản phẩm',
            'description' => 'mô tả chi tiết',
            'short_description' => 'mô tả ngắn',
            'quantity' => 'số lượng tồn',
            'category_id' => 'danh mục',
            'variant_name' => 'tên biến thể',
            'variant_options.*.option' => 'giá trị biến thể',
            'variant_options.*.price_modifier' => 'giá chênh lệch',
            'variant_options.*.quantity' => 'số lượng tồn',
        ];
    }
}
