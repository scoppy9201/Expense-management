<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý chi tiêu</title>
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
            height: 580px;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s ease-in-out;
        }

        .sign-in-container {
            left: 0;
            width: 50%;
            z-index: 2;
        }

        .sign-up-container {
            left: 0;
            width: 50%;
            opacity: 0;
            z-index: 1;
        }

        .container.right-panel-active .sign-in-container {
            transform: translateX(100%);
        }

        .container.right-panel-active .sign-up-container {
            transform: translateX(100%);
            opacity: 1;
            z-index: 5;
            animation: show 0.6s;
        }

        @keyframes show {
            0%, 49.99% {
                opacity: 0;
                z-index: 1;
            }
            50%, 100% {
                opacity: 1;
                z-index: 5;
            }
        }

        form {
            background: #fff;
            display: flex;
            flex-direction: column;
            padding: 40px 50px;
            height: 100%;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        h2 {
            color: #2a5298;
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .form-group {
            width: 100%;
            margin-bottom: 12px;
            text-align: left;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #333;
            font-weight: 500;
            font-size: 13px;
        }

        .input-wrapper {
            position: relative;
            width: 100%;
        }

        .input-wrapper input {
            width: 100%;
            padding: 11px 45px 11px 45px;
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

        .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            width: 20px;
            height: 20px;
            cursor: pointer;
            opacity: 0.6;
            transition: opacity 0.3s;
        }

        .toggle-password:hover {
            opacity: 1;
        }

        .toggle-password img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
            color: #666;
            cursor: pointer;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
            accent-color: #4a90e2;
        }

        .forgot-link {
            color: #4a90e2;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s;
        }

        .forgot-link:hover {
            color: #2a5298;
            text-decoration: underline;
        }

        .submit-btn {
            width: 100%;
            padding: 12px;
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
            margin-top: 8px;
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(42, 82, 152, 0.4);
        }

        .submit-btn:active {
            transform: translateY(-1px);
        }

        .social-login {
            margin-top: 18px;
            width: 100%;
        }

        .social-login p {
            color: #999;
            font-size: 12px;
            margin-bottom: 10px;
        }

        .social-icons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .social-icon {
            width: 40px;
            height: 40px;
            border: 2px solid #e0e0e0;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
            text-decoration: none;
            padding: 7px;
        }

        .social-icon img {
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        .social-icon:hover {
            border-color: #4a90e2;
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
        }

        /* Overlay */
        .overlay-container {
            position: absolute;
            top: 0;
            left: 50%;
            width: 50%;
            height: 100%;
            overflow: hidden;
            transition: transform 0.6s ease-in-out;
            z-index: 100;
        }

        .container.right-panel-active .overlay-container {
            transform: translateX(-100%);
        }

        .overlay {
            background: linear-gradient(135deg, #4a90e2 0%, #2a5298 50%, #1e3c72 100%);
            background-size: cover;
            color: #fff;
            position: relative;
            left: -100%;
            height: 100%;
            width: 200%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .container.right-panel-active .overlay {
            transform: translateX(50%);
        }

        .overlay-panel {
            position: absolute;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 0 50px;
            text-align: center;
            top: 0;
            height: 100%;
            width: 50%;
            transform: translateX(0);
            transition: transform 0.6s ease-in-out;
        }

        .overlay-left {
            transform: translateX(-20%);
        }

        .container.right-panel-active .overlay-left {
            transform: translateX(0);
        }

        .overlay-right {
            right: 0;
            transform: translateX(0);
        }

        .container.right-panel-active .overlay-right {
            transform: translateX(20%);
        }

        .overlay-panel h1 {
            font-size: 28px;
            font-weight: 700;
            white-space: nowrap;
            margin-bottom: 20px;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
        }

        .overlay-panel p {
            font-size: 16px;
            font-weight: 400;
            line-height: 1.7;
            margin-bottom: 35px;
            opacity: 0.95;
        }

        .ghost-btn {
            background: transparent;
            border: 2.5px solid white;
            color: white;
            padding: 14px 55px;
            border-radius: 30px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .ghost-btn:hover {
            background: white;
            color: #2a5298;
            transform: scale(1.08);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                max-width: 400px;
                min-height: auto;
            }

            .form-container {
                position: relative;
                width: 100% !important;
                left: 0 !important;
                transform: none !important;
            }

            .sign-in-container,
            .sign-up-container {
                width: 100%;
                position: relative;
            }

            .container.right-panel-active .sign-in-container {
                display: none;
            }

            .container.right-panel-active .sign-up-container {
                display: block;
                transform: none;
            }

            .overlay-container {
                display: none;
            }

            form {
                padding: 40px 30px;
            }

            h2 {
                font-size: 28px;
            }

            .mobile-switch {
                display: block;
                margin-top: 20px;
                text-align: center;
                color: #666;
                font-size: 14px;
            }

            .mobile-switch button {
                background: none;
                border: none;
                color: #4a90e2;
                font-weight: 600;
                cursor: pointer;
                font-size: 14px;
                text-decoration: underline;
            }
        }

        @media (min-width: 769px) {
            .mobile-switch {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div class="container" id="container">
        <div class="form-container sign-up-container">
            <form action="/register" method="POST">
                @csrf
                <h2>Đăng ký</h2>
                <p class="subtitle">Tạo tài khoản mới</p>
                
                <div class="form-group">
                    <label>Họ và tên</label>
                    <div class="input-wrapper">
                        <img src="/images/user.png" class="input-icon" alt="User Icon">
                        <input type="text" name="name" placeholder="Nhập họ và tên của bạn" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <img src="/images/envelope.png" class="input-icon" alt="Email Icon">
                        <input type="email" name="email" placeholder="example@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <div class="input-wrapper">
                        <img src="/images/pass.png" class="input-icon" alt="Password Icon">
                        <input type="password" id="register-password" name="password" placeholder="Nhập mật khẩu" required>
                        <span class="toggle-password" onclick="togglePassword('register-password', this)">
                            <img src="/images/eye.png" alt="Toggle Password">
                        </span>
                    </div>
                </div>

                <div class="form-group">
                    <label>Xác nhận mật khẩu</label>
                    <div class="input-wrapper">
                        <img src="/images/password.png" class="input-icon" alt="Confirm Password Icon">
                        <input type="password" id="register-password-confirm" name="password_confirmation" placeholder="Nhập lại mật khẩu" required>
                        <span class="toggle-password" onclick="togglePassword('register-password-confirm', this)">
                            <img src="/images/eye.png" alt="Toggle Password">
                        </span>
                    </div>
                </div>

                <button type="submit" class="submit-btn">Đăng ký</button>

                <div class="social-login">
                    <p>hoặc đăng ký bằng</p>
                    <div class="social-icons">
                        <a href="/auth/google" class="social-icon" title="Google">
                             <img src="/images/google.png" alt="Google">
                        </a>
                        <a href="#" class="social-icon" title="Facebook">
                            <img src="/images/facebook.png" alt="Facebook">
                        </a>
                        <a href="#" class="social-icon" title="GitHub">
                            <img src="/images/github.png" alt="GitHub">
                        </a>
                        <a href="#" class="social-icon" title="LinkedIn">
                            <img src="/images/linkedin.png" alt="LinkedIn">
                        </a>
                    </div>
                </div>

                <div class="mobile-switch">
                    Đã có tài khoản? <button type="button" onclick="switchToSignIn()">Đăng nhập</button>
                </div>
            </form>
        </div>

        <div class="form-container sign-in-container">
            <form action="/login" method="POST">
                @csrf
                <h2>Đăng nhập</h2>
                <p class="subtitle">Đăng nhập vào tài khoản của bạn</p>
                
                <div class="form-group">
                    <label>Email</label>
                    <div class="input-wrapper">
                        <img src="/images/envelope.png" class="input-icon" alt="Email Icon">
                        <input type="email" name="email" placeholder="example@email.com" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>Mật khẩu</label>
                    <div class="input-wrapper">
                        <img src="/images/pass.png" class="input-icon" alt="Password Icon">
                        <input type="password" id="login-password" name="password" placeholder="Nhập mật khẩu" required>
                        <span class="toggle-password" onclick="togglePassword('login-password', this)">
                            <img src="/images/eye.png" alt="Toggle Password">
                        </span>
                    </div>
                </div>

                <div class="form-options">
                    <label class="remember-me">
                        <input type="checkbox" name="remember">
                        <span>Ghi nhớ đăng nhập</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="forgot-link">Quên mật khẩu?</a>
                </div>

                <button type="submit" class="submit-btn">Đăng nhập</button>

                <div class="social-login">
                    <p>hoặc đăng nhập bằng</p>
                    <div class="social-icons">
                        <a href="/auth/google" class="social-icon" title="Google">
                            <img src="/images/google.png" alt="Google">
                        </a>
                        <a href="#" class="social-icon" title="Facebook">
                            <img src="/images/facebook.png" alt="Facebook">
                        </a>
                        <a href="#" class="social-icon" title="GitHub">
                            <img src="/images/github.png" alt="GitHub">
                        </a>
                        <a href="#" class="social-icon" title="LinkedIn">
                            <img src="/images/linkedin.png" alt="LinkedIn">
                        </a>
                    </div>
                </div>

                <div class="mobile-switch">
                    Chưa có tài khoản? <button type="button" onclick="switchToSignUp()">Đăng ký ngay</button>
                </div>
            </form>
        </div>

        <!-- Overlay -->
        <div class="overlay-container">
            <div class="overlay">
                <div class="overlay-panel overlay-left">
                    <h1>Xin chào, Chào mừng!</h1>
                    <p>Đã có tài khoản rồi?<br>Đăng nhập ngay để tiếp tục</p>
                    <button class="ghost-btn" id="signIn">Đăng nhập</button>
                </div>
                <div class="overlay-panel overlay-right">
                    <h1>Xin chào, Chào mừng!</h1>
                    <p>Chưa có tài khoản?<br>Đăng ký ngay để bắt đầu</p>
                    <button class="ghost-btn" id="signUp">Đăng ký</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        const signUpButton = document.getElementById('signUp');
        const signInButton = document.getElementById('signIn');
        const container = document.getElementById('container');

        signUpButton.addEventListener('click', () => {
            container.classList.add('right-panel-active');
        });

        signInButton.addEventListener('click', () => {
            container.classList.remove('right-panel-active');
        });

        function switchToSignUp() {
            container.classList.add('right-panel-active');
        }

        function switchToSignIn() {
            container.classList.remove('right-panel-active');
        }

        function togglePassword(inputId, toggleIcon) {
            const input = document.getElementById(inputId);
            const img = toggleIcon.querySelector('img');
            
            if (input.type === 'password') {
                input.type = 'text';
                img.src = '/images/eye.png'
            } else {
                input.type = 'password';
                img.src = '/images/eye_off.png'; 
            }
        }
    </script>
</body>
</html>