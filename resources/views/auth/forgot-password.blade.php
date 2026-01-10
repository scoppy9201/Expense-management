<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>
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
            display: flex;
        }

        .form-section {
            width: 50%;
            padding: 50px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: #4a90e2;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 20px;
            transition: all 0.3s;
        }

        .back-link:hover {
            color: #2a5298;
            gap: 12px;
        }

        .back-link img {
            width: 16px;
            height: 16px;
            object-fit: contain;
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
            line-height: 1.6;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
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
            padding: 11px 45px;
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
            width: 20px;
            height: 20px;
            object-fit: contain;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 6px;
            display: block;
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

        .info-box {
            background: #e7f3ff;
            border-left: 4px solid #4a90e2;
            padding: 12px 15px;
            border-radius: 8px;
            margin-top: 20px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .info-box img {
            width: 20px;
            height: 20px;
            object-fit: contain;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .info-box p {
            color: #1e3c72;
            font-size: 12px;
            line-height: 1.6;
            margin: 0;
            flex: 1;
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
            padding: 50px;
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
            text-decoration: none;
            display: inline-block;
        }

        .ghost-btn:hover {
            background: white;
            color: #2a5298;
            transform: scale(1.08);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.3);
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
            width: 100%;
            height: 100%;
            object-fit: contain;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .container {
                max-width: 400px;
                height: auto;
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
            <a href="{{ route('login') }}" class="back-link">
                <img src="/images/arrow.png" alt="Quay lại">
                <span>Quay lại đăng nhập</span>
            </a>

            <h2>Quên mật khẩu?</h2>
            <p class="subtitle">Nhập email của bạn và chúng tôi sẽ gửi mã xác thực để đặt lại mật khẩu</p>

            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    @foreach($errors->all() as $error)
                        {{ $error }}
                    @endforeach
                </div>
            @endif

            <form action="{{ route('password.email') }}" method="POST">
                @csrf
                
                <div class="form-group">
                    <label>Email đã đăng ký</label>
                    <div class="input-wrapper {{ $errors->has('email') ? 'error' : '' }}">
                        <img src="/images/mail.png" class="input-icon" alt="Email Icon">
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="example@email.com" 
                            value="{{ old('email') }}"
                            required
                            autofocus
                        >
                    </div>
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="submit-btn">Gửi mã xác thực</button>
            </form>

            <div class="info-box">
                <img src="/images/info.png" alt="Thông tin">
                <p><strong>Lưu ý:</strong> Mã xác thực sẽ được gửi đến email của bạn và có hiệu lực trong 3 phút.</p>
            </div>
        </div>

        <div class="overlay-panel">
            <div class="icon-wrapper">
                <img src="/images/lock.png" alt="Lock Icon">
            </div>
            <h1>Đặt lại mật khẩu</h1>
            <p>Bạn sẽ nhận được mã xác thực 6 số qua email.<br>Sử dụng mã này để tạo mật khẩu mới cho tài khoản của bạn.</p>
            <a href="{{ route('login') }}" class="ghost-btn">Đăng nhập</a>
        </div>
    </div>
</body>
</html>