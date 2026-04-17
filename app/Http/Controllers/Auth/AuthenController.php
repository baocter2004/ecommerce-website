<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AuthenController extends Controller
{
    public function showFormRegister()
    {
        return view('auth.register');
    }
    public function handleRegister(StoreUserRequest $request)
    {
        $data = $request->validated();

        try {
            $user = User::query()->create($data);

            Auth::login($user);

            request()->session()->regenerate();

            return redirect()->route('client.index')->with('success', true);
        } catch (\Throwable $th) {
            return back()->withErrors(['register' => 'Đăng ký thất bại: ' . $th->getMessage()])->withInput();
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
            'email' => 'Email hoặc mật khẩu không chính xác.',
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
