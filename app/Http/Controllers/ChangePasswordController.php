<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use App\Models\User;

class ChangePasswordController extends Controller
{
    // Hiển thị form đổi mật khẩu
    public function showChangeForm()
    {
        return view('change-password');
    }

    // Xử lý đổi mật khẩu
    public function changePassword(Request $request)
    {
        // Validate dữ liệu
        $request->validate([
            'current_password' => 'required',
            'password' => [
                'required',
                'min:8',
                'confirmed',
                'different:current_password'
            ],
            'password_confirmation' => 'required'
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại',
            'password.required' => 'Vui lòng nhập mật khẩu mới',
            'password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
            'password.different' => 'Mật khẩu mới phải khác mật khẩu hiện tại',
            'password_confirmation.required' => 'Vui lòng xác nhận mật khẩu mới'
        ]);

        // Kiểm tra mật khẩu hiện tại có đúng không
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Mật khẩu hiện tại không đúng'
            ])->withInput();
        }

        // Cập nhật mật khẩu mới
        User::where('id', Auth::id())->update([
            'password' => Hash::make($request->password)
        ]);

        // Đăng xuất tất cả các session khác
        Auth::logoutOtherDevices($request->password);

        return redirect()->route('change-password.form')
            ->with('success', 'Đổi mật khẩu thành công!');
    }
}