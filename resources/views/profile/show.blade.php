<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hồ sơ cá nhân - Quản lý chi tiêu</title>
    <style>
        /* Reset css */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body + nền + font chính */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e94c5 100%);
            min-height: 100vh;
            padding: 30px 20px;
        }

        /* container giới hạn chiều rộng */
        .container {
            max-width: 1100px;
            margin: 0 auto;
        }

        /* Header (tiêu đè + nút quay lại) */
        .page-header {
            background: white;
            padding: 30px 35px;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-direction: row-reverse;
        }

        /*nhóm tiêu đề trang*/
        .page-title {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .page-title img {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .page-title h1 {
            color: #2a5298;
            font-size: 32px;
            font-weight: 700;
        }

        /* nút quay lại */
        .back-btn {
            background: linear-gradient(135deg, #4a90e2 0%, #2a5298 100%);
            color: white;
            border: none;
            padding: 12px 28px;
            border-radius: 30px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(42, 82, 152, 0.3);
        }

        .back-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(42, 82, 152, 0.4);
        }

        .back-btn img {
            width: 18px;
            height: 18px;
        }

        /* Profile Layout */
        .profile-container {
            display: grid;
            grid-template-columns: 380px 1fr;
            gap: 30px;
            align-items: stretch;
        }

        /* Avatar Card */
        .avatar-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            text-align: center;
            display: flex;
            flex-direction: column;
        }

        /* Avatar dưới dạng text */
        .avatar-text {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: linear-gradient(135deg, #4a90e2 0%, #2a5298 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 48px;
        }

        .avatar-container {
            position: relative;
            width: 180px;
            height: 180px;
            margin: 0 auto 25px;
        }

        .avatar-frame {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            overflow: hidden;
            border: 5px solid transparent;
            background: linear-gradient(white, white) padding-box,
                        linear-gradient(135deg, #4a90e2, #2a5298) border-box;
            box-shadow: 0 10px 40px rgba(42, 82, 152, 0.2);
            position: relative;
        }

        .avatar-frame img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .avatar-overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0,0,0,0.8), transparent);
            padding: 15px;
            cursor: pointer;
            opacity: 0;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: white;
            font-size: 13px;
            font-weight: 600;
        }

        .avatar-overlay img {
            width: 16px;
            height: 16px;
        }

        .avatar-container:hover .avatar-overlay {
            opacity: 1;
        }

        .avatar-actions {
            display: flex;
            gap: 12px;
            margin-top: 20px;
        }

        .avatar-btn {
            flex: 1;
            padding: 10px 20px;
            border: 2px solid #4a90e2;
            background: white;
            color: #4a90e2;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        .avatar-btn img {
            width: 16px;
            height: 16px;
        }

        .avatar-btn:hover {
            background: #4a90e2;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(74, 144, 226, 0.3);
        }

        .avatar-btn:hover img {
            filter: brightness(0) invert(1);
        }

        .avatar-btn.delete {
            border-color: #dc3545;
            color: #dc3545;
        }

        .avatar-btn.delete:hover {
            background: #dc3545;
            color: white;
            box-shadow: 0 6px 20px rgba(220, 53, 69, 0.3);
        }

        /* User Info */
        .user-header {
            margin-top: 25px;
            padding-top: 25px;
            border-top: 2px solid #f0f0f0;
        }

        .user-name {
            font-size: 24px;
            font-weight: 700;
            color: #2a5298;
            margin-bottom: 8px;
        }

        .user-email {
            color: #666;
            font-size: 14px;
            margin-bottom: 18px;
            word-break: break-all;
        }

        .account-type {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 600;
        }

        .account-type.google {
            background: linear-gradient(135deg, #e7f3ff 0%, #d4e9ff 100%);
            color: #4a90e2;
            border: 2px solid #4a90e2;
        }

        .account-type.system {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border: 2px solid #28a745;
        }

        .account-type img {
            width: 20px;
            height: 20px;
        }

        /* Info Stats */
        .info-stats {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 25px;
        }

        .stat-item {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            padding: 15px;
            border-radius: 12px;
            text-align: center;
        }

        .stat-label {
            font-size: 12px;
            color: #666;
            margin-bottom: 5px;
        }

        .stat-value {
            font-size: 16px;
            font-weight: 700;
            color: #2a5298;
        }

        /* Form Card */
        .form-card {
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.15);
            display: flex;
            flex-direction: column;
        }

        .form-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #f0f0f0;
        }

        .form-header img {
            width: 32px;
            height: 32px;
        }

        .form-header h2 {
            color: #2a5298;
            font-size: 24px;
            font-weight: 700;
        }

        /* Alerts */
        .alert {
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 14px;
            display: flex;
            align-items: flex-start;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }

        .alert img {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .alert-success {
            background: linear-gradient(135deg, #d4edda 0%, #c3e6cb 100%);
            color: #155724;
            border-left: 4px solid #28a745;
        }

        .alert-error {
            background: linear-gradient(135deg, #f8d7da 0%, #f5c6cb 100%);
            color: #721c24;
            border-left: 4px solid #dc3545;
        }

        .notice-box {
            background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%);
            border-left: 4px solid #ffc107;
            padding: 15px 20px;
            border-radius: 12px;
            margin-bottom: 25px;
            font-size: 13px;
            color: #856404;
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .notice-box img {
            width: 20px;
            height: 20px;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* Form Styles */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group.full {
            grid-column: 1 / -1;
        }

        .form-label {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 10px;
            font-size: 14px;
            font-weight: 600;
            color: #333;
        }

        .label-badge {
            background: #ffc107;
            color: #856404;
            font-size: 10px;
            padding: 3px 10px;
            border-radius: 12px;
            font-weight: 700;
            text-transform: uppercase;
            display: inline-flex;
            align-items: center;
            gap: 4px;
        }

        .label-badge img {
            width: 12px;
            height: 12px;
        }

        .form-input {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s;
            outline: none;
            background: #f8f9fa;
            font-family: inherit;
        }

        .form-input:focus {
            border-color: #4a90e2;
            background: white;
            box-shadow: 0 0 0 4px rgba(74, 144, 226, 0.1);
        }

        .form-input:disabled {
            background: linear-gradient(135deg, #e9ecef 0%, #dee2e6 100%);
            cursor: not-allowed;
            color: #6c757d;
        }

        .error-text {
            color: #dc3545;
            font-size: 12px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .error-text img {
            width: 14px;
            height: 14px;
        }

        .submit-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #4a90e2 0%, #2a5298 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            box-shadow: 0 6px 20px rgba(42, 82, 152, 0.3);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .submit-btn img {
            width: 20px;
            height: 20px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(42, 82, 152, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        #avatarInput {
            display: none;
        }

        /* Responsive */
        @media (max-width: 968px) {
            .profile-container {
                grid-template-columns: 1fr;
            }

            .avatar-card {
                position: relative;
                top: 0;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 576px) {
            .page-header {
                flex-direction: column;
                gap: 20px;
                text-align: center;
            }

            .page-title h1 {
                font-size: 24px;
            }

            .avatar-container {
                width: 150px;
                height: 150px;
            }

            .avatar-actions {
                flex-direction: column;
            }

            .info-stats {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Page Header -->
        <div class="page-header">
            <div class="page-title">
                <img src="/images/user.png" alt="Profile">
                <h1>Hồ sơ cá nhân</h1>
            </div>
            <a href="{{ route('dashboard') }}" class="back-btn">
                <img src="/images/arrow.png" alt="Back">
                Quay lại Dashboard
            </a>
        </div>

        <div class="profile-container">
            <!-- Avatar Card -->
            <div class="avatar-card">
                <div class="avatar-container">
                    <div class="avatar-frame">
                        <div class="avatar-frame">
                            @if($user->avatar)
                                @if(str_starts_with($user->avatar, 'http'))
                                    <img id="avatarPreview" src="{{ $user->avatar }}" alt="Avatar">
                                @else
                                    <img id="avatarPreview" src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar">
                                @endif
                            @else
                                <div id="avatarPreview" class="avatar-text">
                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                </div>
                            @endif

                            <label for="avatarInput" class="avatar-overlay">
                                <img src="/images/camera.png" alt="Camera">
                                Thay đổi ảnh
                            </label>
                        </div>
                    </div>
                </div>

                <form action="{{ route('profile.avatar.update') }}" method="POST" enctype="multipart/form-data" id="avatarForm">
                    @csrf
                    <input type="file" name="avatar" id="avatarInput" accept="image/*">
                </form>

                <div class="avatar-actions">
                    <button class="avatar-btn" onclick="document.getElementById('avatarInput').click()">
                        <img src="/images/upload.png" alt="Upload">
                        Tải ảnh lên
                    </button>
                    @if($user->avatar)
                    <form action="{{ route('profile.avatar.delete') }}" method="POST" style="display: inline; flex: 1;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="avatar-btn delete" onclick="return confirm('Bạn có chắc muốn xóa ảnh đại diện?')" style="width: 100%;">
                            <img src="/images/trash.png" alt="Delete">
                            Xóa ảnh
                        </button>
                    </form>
                    @endif
                </div>

                <div class="user-header">
                    <div class="user-name">{{ $user->name }}</div>
                    <div class="user-email">{{ $user->email }}</div>
                    
                    @if($user->google_id)
                        <div class="account-type google">
                            <img src="/images/google.png" alt="Google">
                            Tài khoản Google
                        </div>
                    @else
                        <div class="account-type system">
                            <img src="/images/lock.png" alt="Lock">
                            Tài khoản hệ thống
                        </div>
                    @endif
                </div>

                <div class="info-stats">
                    <div class="stat-item">
                        <div class="stat-label">Giới tính</div>
                        <div class="stat-value">{{ $user->gioi_tinh ?? '---' }}</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-label">Ngày sinh</div>
                        <div class="stat-value">
                            @if($user->ngay_sinh)
                                {{ \Carbon\Carbon::parse($user->ngay_sinh)->format('d/m/Y') }}
                            @else
                                ---
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="form-card">
                <div class="form-header">
                    <img src="/images/edit.png" alt="Edit">
                    <h2>Chỉnh sửa thông tin</h2>
                </div>

                @if(session('success'))
                    <div class="alert alert-success">
                        <img src="/images/check.png" alt="Success">
                        <div>{{ session('success') }}</div>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-error">
                        <img src="/images/warning.png" alt="Error">
                        <div>
                            @foreach($errors->all() as $error)
                                {{ $error }}<br>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($user->google_id)
                    <div class="notice-box">
                        <img src="/images/info.png" alt="Info">
                        <div>
                            <strong>Lưu ý:</strong> Tài khoản Google không thể thay đổi Họ tên và Email. 
                            Bạn chỉ có thể cập nhật các thông tin bổ sung khác.
                        </div>
                    </div>
                @endif

                <form action="{{ route('profile.update') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-grid">
                        <!-- Họ và tên -->
                        <div class="form-group">
                            <label class="form-label">
                                Họ và tên
                                @if($user->google_id)
                                    <span class="label-badge">
                                        <img src="/images/locked.png" alt="Lock">
                                        Khóa
                                    </span>
                                @endif
                            </label>
                            <input 
                                type="text" 
                                name="name" 
                                class="form-input"
                                value="{{ old('name', $user->name) }}"
                                placeholder="Nhập họ và tên"
                                {{ $user->google_id ? 'disabled' : 'required' }}
                            >
                            @error('name')
                                <span class="error-text">
                                    <img src="/images/warning.png" alt="Alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="form-group">
                            <label class="form-label">
                                Email
                                @if($user->google_id)
                                    <span class="label-badge">
                                        <img src="/images/locked.png" alt="Lock">
                                        Khóa
                                    </span>
                                @endif
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                class="form-input"
                                value="{{ old('email', $user->email) }}"
                                placeholder="email@example.com"
                                {{ $user->google_id ? 'disabled' : 'required' }}
                            >
                            @error('email')
                                <span class="error-text">
                                    <img src="/images/warning.png" alt="Alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Số điện thoại -->
                        <div class="form-group">
                            <label class="form-label">Số điện thoại</label>
                            <input 
                                type="tel" 
                                name="phone" 
                                class="form-input"
                                value="{{ old('phone', $user->phone) }}"
                                placeholder="0123456789"
                                maxlength="15"
                            >
                            @error('phone')
                                <span class="error-text">
                                    <img src="/images/warning.png" alt="Alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Ngày sinh -->
                        <div class="form-group">
                            <label class="form-label">Ngày sinh</label>
                            <input 
                                type="date" 
                                name="ngay_sinh" 
                                class="form-input"
                                value="{{ old('ngay_sinh', $user->ngay_sinh ? \Carbon\Carbon::parse($user->ngay_sinh)->format('Y-m-d') : '') }}"
                                max="{{ date('Y-m-d') }}"
                            >
                            @error('ngay_sinh')
                                <span class="error-text">
                                    <img src="/images/warning.png" alt="Alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>

                        <!-- Giới tính -->
                        <div class="form-group full">
                            <label class="form-label">Giới tính</label>
                            <select name="gioi_tinh" class="form-input">
                                <option value="">-- Chọn giới tính --</option>
                                <option value="Nam" {{ old('gioi_tinh', $user->gioi_tinh) == 'Nam' ? 'selected' : '' }}>Nam</option>
                                <option value="Nữ" {{ old('gioi_tinh', $user->gioi_tinh) == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                                <option value="Khác" {{ old('gioi_tinh', $user->gioi_tinh) == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('gioi_tinh')
                                <span class="error-text">
                                    <img src="/images/warning.png" alt="Alert">
                                    {{ $message }}
                                </span>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="submit-btn">
                        <img src="/images/save.png" alt="Save">
                        Lưu thay đổi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview và auto-submit avatar
        document.getElementById('avatarInput').addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const file = e.target.files[0];
                
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ảnh không được vượt quá 2MB!');
                    e.target.value = '';
                    return;
                }

                // Validate file type
                if (!file.type.match('image.*')) {
                    alert('Vui lòng chọn file ảnh!');
                    e.target.value = '';
                    return;
                }

                // Preview image
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('avatarPreview').src = e.target.result;
                }
                reader.readAsDataURL(file);
                
                // Auto submit
                setTimeout(() => {
                    document.getElementById('avatarForm').submit();
                }, 500);
            }
        });

        // Tự động ẩn tất cả thông báo .alert sau 5s 
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                alert.style.animation = 'slideDown 0.3s ease reverse';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
    </script>
</body>
</html>