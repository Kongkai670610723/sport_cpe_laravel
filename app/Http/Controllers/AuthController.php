<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        // Check password (supports raw demo password 'password123' / 'admin123' or bcrypt)
        if ($user && ($user->password_hash === $credentials['password'] || password_verify($credentials['password'], $user->password_hash))) {
            Auth::login($user);
            $request->session()->regenerate();

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard')->with('success', 'ยินดีต้อนรับสู่ระบบผู้ดูแลระบบ');
            }

            return redirect()->intended(route('home'))->with('success', 'เข้าสู่ระบบสำเร็จ');
        }

        return back()->withErrors([
            'email' => 'อีเมลหรือรหัสผ่านไม่ถูกต้อง',
        ])->withInput($request->only('email'));
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:150',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone'    => 'nullable|string|max:20',
            'address'  => 'nullable|string',
        ]);

        $user = User::create([
            'name'          => $validated['name'],
            'email'         => $validated['email'],
            'password_hash' => $validated['password'],
            'phone'         => $validated['phone'],
            'address'       => $validated['address'],
            'role'          => 'customer',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'สมัครสมาชิกสำเร็จ ยินดีต้อนรับสู่ SPORT.CPE!');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'ออกจากระบบเรียบร้อยแล้ว');
    }
}
