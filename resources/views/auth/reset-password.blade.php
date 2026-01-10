<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Đặt lại mật khẩu</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 50%, #7e94c5 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
        }

        .container {
            background: white;
            border-radius: 25px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.35);
            position: relative;
            overflow: hidden;
            width: 100%;
            max-width: 900px;
            min-height: 580px;
            display: flex;
        }

        .form-section {
            width: 50%;
            padding: 40px 45px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        h2 {
            color: #2a5298;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: center;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 30px;
            text-align: center;
            line-height: 1.6;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .form-group {
            width: 100%;
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-wrapper input {
            width: 100%;
            padding: 13px 45px 13px 45px;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            outline: none;
            background: #f8f9fa;
        }

        .input-wrapper input:focus {
            border-color: #4a90e2;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .input-wrapper.error input {
            border-color: #dc3545;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            object-fit: contain;
            opacity: 0.6;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            cursor: pointer;
            opacity: 0.5;
            transition: opacity 0.3s;
        }

        .toggle-password:hover {
            opacity: 0.8;
        }

        .toggle-password img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 6px;
            display: block;
        }

        .password-requirements {
            background: #f0f7ff;
            border-left: 4px solid #4a90e2;
            padding: 12px 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .password-requirements p {
            color: #2a5298;
            font-size: 13px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .password-requirements ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .password-requirements li {
            color: #555;
            font-size: 13px;
            padding-left: 20px;
            position: relative;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        .password-requirements li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #28a745;
            font-weight: bold;
            font-size: 14px;
        }

        .submit-btn {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4a90e2 0%, #2a5298 100%);
            color: white;
            border: none;
            border-radius: 30px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.2px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(42, 82, 152, 0.4);
        }

        /* Overlay Panel */
        .overlay-panel {
            width: 50%;
            background: linear-gradient(135deg, #4a90e2 0%, #2a5298 50%, #1e3c72 100%);
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px 45px;
            text-align: center;
        }

        .overlay-panel h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .overlay-panel p {
            font-size: 16px;
            line-height: 1.7;
            margin-bottom: 30px;
            opacity: 0.95;
        }

        .icon-wrapper {
            width: 120px;
            height: 120px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            backdrop-filter: blur(10px);
            padding: 30px;
        }

        .icon-wrapper img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .security-features {
            width: 100%;
            max-width: 320px;
            margin-top: 10px;
        }

        .feature {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 18px;
            font-size: 15px;
            text-align: left;
        }

        .feature-icon {
            min-width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.25);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .feature-icon img {
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                max-width: 400px;
                min-height: auto;
                flex-direction: column;
            }

            .form-section {
                width: 100%;
                padding: 40px 30px;
            }

            .overlay-panel {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Đặt lại mật khẩu</h2>
            <p class="subtitle">Nhập mật khẩu mới cho tài khoản của bạn</p>

            <div class="alert alert-error" style="display: none;">
                Có lỗi xảy ra
            </div>

            <form action="{{ route('password.update') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label>Mật khẩu mới</label>
                    <div class="input-wrapper">
                        <img src="{{ asset('images/pass.png') }}" class="input-icon" alt="Password Icon">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Nhập mật khẩu mới"
                            required
                            autofocus
                        >
                        <span class="toggle-password" onclick="togglePassword('password', this)">
                            <img src="{{ asset('images/eye.png') }}" alt="Toggle Password">
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Xác nhận mật khẩu mới</label>
                    <div class="input-wrapper">
                        <img src="{{ asset('images/password.png') }}" class="input-icon" alt="Confirm Password Icon">
                        <input 
                            type="password" 
                            id="password_confirmation" 
                            name="password_confirmation" 
                            placeholder="Nhập lại mật khẩu mới"
                            required
                        >
                        <span class="toggle-password" onclick="togglePassword('password_confirmation', this)">
                            <img src="{{ asset('images/eye.png') }}" alt="Toggle Password">
                        </span>
                    </div>
                </div>

                <div class="password-requirements">
                    <p>Yêu cầu mật khẩu:</p>
                    <ul>
                        <li>Tối thiểu 8 ký tự</li>
                        <li>Nên có chữ hoa, chữ thường và số</li>
                        <li>Không trùng với mật khẩu cũ</li>
                    </ul>
                </div>

                <button type="submit" class="submit-btn">Đặt lại mật khẩu</button>
            </form>
        </div>

        <div class="overlay-panel">
            <div class="icon-wrapper">
                <img src="{{ asset('images/insurance.png') }}" alt="Insurance Icon">
            </div>
            <h1>Bảo mật tài khoản</h1>
            <p>Tạo mật khẩu mạnh để bảo vệ tài khoản của bạn khỏi các truy cập trái phép.</p>
            
            <div class="security-features">
                <div class="feature">
                    <div class="feature-icon">
                        <img src="{{ asset('images/lock.png') }}" alt="Lock Icon">
                    </div>
                    <div>Mã hóa an toàn</div>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <img src="{{ asset('images/check.png') }}" alt="Check Icon">
                    </div>
                    <div>Xác thực 2 lớp</div>
                </div>
                <div class="feature">
                    <div class="feature-icon">
                        <img src="{{ asset('images/technology.png') }}" alt="Security Icon">
                    </div>
                    <div>Bảo vệ dữ liệu</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId, toggleIcon) {
            const passwordField = document.getElementById(fieldId);
            const iconImg = toggleIcon.querySelector('img');
            if (passwordField.type === "password") {
                passwordField.type = "text";
                iconImg.src = "{{ asset('images/eye_off.png') }}";
            } else {
                passwordField.type = "password";
                iconImg.src = "{{ asset('images/eye.png') }}";
            }
        }
    </script>
</body>
</html>
