<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUserRequest extends FormRequest
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
        $id = $this->segment(3);
        return [
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users','email')->ignore($id)
            ],
            // 'password' => 'required|min:10|confirmed',
            'user_name' =>[
                'required',
                Rule::unique('users','user_name')->ignore($id)
            ],
            'image' => 'nullable|image|max:2048',
            'phone' => 'nullable|string|max:14',
            'role' => [
                'required',
                Rule::in([User::ROLE_ADMIN,User::ROLE_MEMBER])
            ],
            'is_active' => [
                'nullable',
                Rule::in([0,1])
            ],
        ];
    }
}
