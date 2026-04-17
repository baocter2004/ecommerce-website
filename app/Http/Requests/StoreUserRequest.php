<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')
            ],
            'password' => 'required|min:8|confirmed',
            'user_name' => [
                'required',
                Rule::unique('users', 'user_name')
            ],
            'image' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:14',
            'role' => [
                'required',
                Rule::in([User::ROLE_ADMIN, User::ROLE_MEMBER])
            ],
            'is_active' => [
                'nullable',
                Rule::in([0, 1])
            ],
        ];
    }

    public function attributes(): array
    {
        return [
            'name' => 'họ và tên',
            'email' => 'địa chỉ email',
            'password' => 'mật khẩu',
            'user_name' => 'tên đăng nhập',
            'image' => 'ảnh đại diện',
            'phone' => 'số điện thoại',
            'role' => 'vai trò',
            'is_active' => 'trạng thái',
        ];
    }
}
