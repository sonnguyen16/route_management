<?php

namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Models\Xa;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function index()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('tuyen-duong.index');
        }
        return Inertia::render('Auth/Login');
    }

    public function login(LoginRequest $request)
    {
        $validated = $request->validated();

        if (!Auth::guard('web')->attempt($validated)) {
            throw ValidationException::withMessages([
                'email' => 'Email hoặc mật khẩu không đúng',
            ]);
        }
        $request->session()->regenerate();

        return redirect()->route('tuyen-duong.index');
    }

    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
