<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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
            "product_name" => 'required',
            "price" => 'required|numeric',
            "product_image" => 'nullable|image|max:2048',
            "description" => 'required',
            "short_description" => 'required',
            "is_active" => [
                'nullable',
                Rule::in([0,1])
            ],
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')
            ],
        ];
    }
}
