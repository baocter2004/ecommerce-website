<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Auth::check();
    }

    public function rules(): array
    {
        $user = Auth::user();

        return [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'user_name' => [
                'required',
                Rule::unique('users', 'user_name')->ignore($user->id),
            ],
            'phone' => 'nullable|string|max:14',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'họ và tên',
            'email' => 'địa chỉ email',
            'user_name' => 'tên đăng nhập',
            'image' => 'ảnh đại diện',
            'phone' => 'số điện thoại',
        ];
    }
}
