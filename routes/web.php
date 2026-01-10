<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CategoryController;

// Trang chủ (chưa đăng nhập)
Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Guest routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/auth/google', [LoginController::class, 'redirectToGoogle'])->name('google.redirect');
    Route::get('/auth/google/callback', [LoginController::class, 'handleGoogleCallback']);

    // Forgot password flow
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetCode'])->name('password.email');
    Route::get('/verify-code', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify.form');
    Route::post('/verify-code', [ForgotPasswordController::class, 'verifyCode'])->name('password.verify');
    Route::get('/reset-password', [ForgotPasswordController::class, 'showResetForm'])->name('password.reset.form');
    Route::post('/reset-password', [ForgotPasswordController::class, 'resetPassword'])->name('password.update');
});

// Authenticated routes - 
Route::middleware('auth')->group(function () {

    // Dashboard 
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::match(['put', 'patch'], '/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])->name('profile.avatar.update');
    Route::delete('/profile/avatar', [ProfileController::class, 'deleteAvatar'])->name('profile.avatar.delete');

    // Change password
    Route::get('/change-password', [ChangePasswordController::class, 'showChangeForm'])->name('change-password.form');
    Route::post('/change-password', [ChangePasswordController::class, 'changePassword'])->name('change-password.update');

    // Quản lý danh mục chi tiêu
    Route::resource('categories', CategoryController::class)->parameters(['categories' => 'category']);
    Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');

    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

// Redirect cũ (giữ lại cho an toàn)
Route::redirect('/home', '/dashboard')->middleware('auth');