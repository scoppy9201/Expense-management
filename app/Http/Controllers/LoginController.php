<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class LoginController extends Controller
{
    // Hiển thị form đăng nhập
    public function showLoginForm()
    {
        return view('auth.AuthForm');
    }

    /**
     * Đăng nhập bằng email + password
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Check brute force
        $this->checkTooManyFailedAttempts($request);

        $credentials = $request->only('email', 'password');
        $remember = $request->filled('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            RateLimiter::clear($this->throttleKey($request));

            return redirect()->route('dashboard')
                ->with('success', 'Đăng nhập thành công!');
        }

        RateLimiter::hit($this->throttleKey($request), 60);

        throw ValidationException::withMessages([
            'email' => 'Email hoặc mật khẩu không đúng.',
        ]);
    }

    /**
     * B1: Redirect sang Google login
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * B2: Nhận callback và xử lý
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')
                ->with('error', 'Không thể đăng nhập Google, vui lòng thử lại!');
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Tạo mới user
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
                'password' => Hash::make(Str::random(16)),
            ]);
        } else {
            // Update user cũ
            $user->google_id = $googleUser->getId();
            $user->avatar = $googleUser->getAvatar();
            $user->save(); // <- Quan trọng!!!
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->route('dashboard')
                ->with('success', 'Đăng nhập thành công!');
    }

    /**
     * Đăng xuất
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Đã đăng xuất thành công!');
    }

    /**
     * Chống brute force
     */
    protected function checkTooManyFailedAttempts(Request $request)
    {
        if (!RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey($request));

        throw ValidationException::withMessages([
            'email' => "Quá nhiều lần đăng nhập sai. Vui lòng thử lại sau {$seconds} giây.",
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::transliterate(
            Str::lower($request->input('email')).'|'.$request->ip()
        );
    }

    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except(['logout', 'handleGoogleCallback']);
        $this->middleware('auth')->only('logout');
    }
}

