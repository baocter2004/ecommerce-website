<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthenController extends Controller
{
    public function showFormRegister()
    {
        return view('auth.register');
    }
    public function handleRegister()
    {
        $data = request()->validate([
            "name" => ['required'],
            "email" => [
                'required',
                Rule::unique('users'),
                'email'
            ],
            "user_name" => [
                'required',
                Rule::unique('users')
            ],
            "password" => [
                'required',
                'confirmed',
                'string',
                'min:8', // Độ dài tối thiểu
                'regex:/[a-z]/', // Ít nhất 1 chữ cái thường
                'regex:/[0-9]/', // Ít nhất 1 chữ số
                'regex:/[\W_]/', // Ít nhất 1 ký tự đặc biệt
            ],
        ]);

        try {
            $user = User::query()->create($data);

            Auth::login($user);

            request()->session()->regenerate();

            return redirect()->route('client.index')->with('success', true);
        } catch (\Throwable $th) {
            // return back()->withErrors($th->getMessage());
            return back()->with('success', false);
        }
    }
    public function showFormLogin()
    {
        return view('auth.login');
    }
    public function handleLogin()
    {
        $credentials = request()->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            request()->session()->regenerate();

            /**
             * @var User
             */
            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('client.index');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }
    public function logout()
    {
        Auth::logout();

        request()->session()->invalidate();

        request()->session()->regenerateToken();

        return redirect('/');
    }
}
