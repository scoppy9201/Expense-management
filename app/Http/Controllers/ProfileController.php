<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\User;

class ProfileController extends Controller
{
    // Hiển thị trang hồ sơ
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }

    // Cập nhật thông tin cá nhân
    public function update(Request $request)
    {
        $userId = Auth::id(); 

        // Validate dữ liệu
        $rules = [
            'phone' => 'nullable|string|max:15|regex:/^[0-9]+$/',
            'ngay_sinh' => 'nullable|date|before:today',
            'gioi_tinh' => 'nullable|in:Nam,Nữ,Khác',
        ];

        if (!$request->user()->google_id) {
            $rules['name'] = 'required|string|max:255';
            $rules['email'] = [
                'required',
                'email',
                Rule::unique('users')->ignore($userId)
            ];
        }

        $validated = $request->validate($rules);

        // Cập nhật thông tin cá nhân 
        $updateData = [
            'phone' => $validated['phone'] ?? null,
            'ngay_sinh' => $validated['ngay_sinh'] ?? null,
            'gioi_tinh' => $validated['gioi_tinh'] ?? null,
        ];

        if (!$request->user()->google_id) {
            $updateData['name'] = $validated['name'];
            $updateData['email'] = $validated['email'];
        }

        User::where('id', $userId)->update($updateData);

        return back()->with('success', 'Cập nhật thông tin thành công!');
    }

    // Cập nhật avatar
    public function updateAvatar(Request $request)
    {
        $userId = Auth::id();

        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        $user = User::find($userId);

        if ($user->avatar && !str_contains($user->avatar, 'default') && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');

        User::where('id', $userId)->update(['avatar' => $path]);

        return back()->with('success', 'Cập nhật ảnh đại diện thành công!');
    }

    // Xóa avatar
    public function deleteAvatar()
    {
        $userId = Auth::id();
        $user = User::find($userId);

        if ($user->avatar && !str_contains($user->avatar, 'default') && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        User::where('id', $userId)->update(['avatar' => null]);

        return back()->with('success', 'Đã xóa ảnh đại diện!');
    }
}
