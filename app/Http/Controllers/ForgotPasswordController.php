<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use Carbon\Carbon;

class ForgotPasswordController extends Controller
{
    // Hiển thị form nhập mail
    public function showLinkRequestForm()
    {
        return view('auth.forgot-password');
    }

    // Gửi mã xác thực 6 số vê email
    public function sendResetCode(Request $request)
    {
        // Validate email
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Vui lòng nhập email',
            'email.email' => 'Email không đúng định dạng',
            'email.exists' => 'Email không tồn tại trong hệ thống',
        ]);

        // Tạo mã xác thực 6 số ngẫu nhiên
        $code = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);

        // Lưu mã vào database
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->input('email')],
            [
                'token' => $code,
                'created_at' => Carbon::now()
            ]
        );

        // Gửi email chứa mã xác thực
        Mail::send('auth.reset-code', ['code' => $code], function ($message) use ($request) {
            $message->to($request->email);
            $message->subject('Mã xác thực đặt lại mật khẩu');
        });
        
        $request->session()->put('email', $request->email);
        $request->session()->save();
        
        return redirect()->route('password.verify.form')
            ->with('success', 'Mã xác thực đã được gửi đến email của bạn!');
    }

    // Hiển thị form nhập mã xác thực
    public function showVerifyForm()
    {
        if (!session('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-code');
    }

    // Xác thực mã và cho phép đặt lại mật khẩu
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|digits:6'
        ], [
            'code.required' => 'Vui lòng nhập mã xác thực',
            'code.digits' => 'Mã xác thực phải là 6 chữ số'
        ]);

        $email = session('email');
        
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $request->code)
            ->first();

        if (!$resetRecord) {
            return back()->withErrors(['code' => 'Mã xác thực không đúng']);
        }

        // Kiểm tra mã còn hiệu lực (3 phút)
        if (Carbon::parse($resetRecord->created_at)->addMinutes(3)->isPast()) {
            return back()->withErrors(['code' => 'Mã xác thực đã hết hạn']);
        }

        // Lưu thông tin vào session để đặt lại mật khẩu
        $request->session()->put('email', $email);
        $request->session()->put('code', $request->code);
        $request->session()->put('code_verified', true);
        
        $request->session()->save();

        return redirect()->route('password.reset.form');
    }

    // Hiển thị form đặt lại mật khẩu
    public function showResetForm()
    {
        if (!session('email') || !session('code')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset-password');
    }

    // Đặt lại mật khẩu mới
    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|min:8|confirmed',
            'password_confirmation' => 'required'
        ], [
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp'
        ]);

        $email = session('email');
        $code = session('code');

        // Xác thực lại mã
        $resetRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('token', $code)
            ->first();

        if (!$resetRecord) {
            return redirect()->route('password.request')
                ->withErrors(['error' => 'Phiên làm việc đã hết hạn']);
        }

        // Cập nhật mật khẩu
        $user = User::where('email', $email)->first();
        $user->password = bcrypt($request->password);
        $user->save();

        // Xóa mã xác thực
        DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Xóa session
        session()->forget(['email', 'code']);

        return redirect()->route('login')
            ->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập.');
    }
}
