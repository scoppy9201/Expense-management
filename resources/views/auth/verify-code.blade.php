<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Xác thực mã</title>
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
            margin-bottom: 8px;
            text-align: center;
            line-height: 1.6;
        }

        .email-display {
            color: #4a90e2;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 30px;
            text-align: center;
        }

        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 20px;
            font-size: 14px;
            text-align: left;
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

        .code-inputs {
            display: flex;
            gap: 8px;
            justify-content: center;
            margin-bottom: 25px;
        }

        .code-input {
            width: 50px;
            height: 55px;
            text-align: center;
            font-size: 22px;
            font-weight: 700;
            border: 2px solid #e0e0e0;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .code-input:focus {
            border-color: #4a90e2;
            background: white;
            box-shadow: 0 0 0 3px rgba(74, 144, 226, 0.1);
        }

        .code-input.error {
            border-color: #dc3545;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: -15px;
            margin-bottom: 20px;
            display: block;
            text-align: center;
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
        }

        .submit-btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(42, 82, 152, 0.4);
        }

        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .resend-section {
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
        }

        .resend-text {
            color: #666;
            font-size: 13px;
            margin-bottom: 10px;
        }

        .resend-btn {
            background: transparent;
            border: 2px solid #4a90e2;
            color: #4a90e2;
            padding: 8px 25px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .resend-btn:hover {
            background: #4a90e2;
            color: white;
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
            margin-bottom: 20px;
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

        .icon-wrapper svg {
            width: 100%;
            height: 100%;
        }

        .icon-wrapper img {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }

        .steps {
            width: 100%;
            max-width: 300px;
            text-align: left;
            margin-top: 15px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 15px;
        }

        .step-number {
            min-width: 30px;
            height: 30px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            font-size: 14px;
            flex-shrink: 0;
        }

        .step-text {
            font-size: 14px;
            line-height: 1.5;
            padding-top: 5px;
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

            .code-input {
                width: 45px;
                height: 50px;
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="form-section">
            <h2>Xác thực mã</h2>
            <p class="subtitle">Mã xác thực đã được gửi đến email của bạn.</p> 
            <p class="email-display">{{ session('email') }}</p>

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

            <form action="{{ route('password.verify') }}" method="POST" id="verifyForm">
                @csrf
                
                <div class="code-inputs">
                    <input type="text" maxlength="1" class="code-input" data-index="0" readonly>
                    <input type="text" maxlength="1" class="code-input" data-index="1" readonly>
                    <input type="text" maxlength="1" class="code-input" data-index="2" readonly>
                    <input type="text" maxlength="1" class="code-input" data-index="3" readonly>
                    <input type="text" maxlength="1" class="code-input" data-index="4" readonly>
                    <input type="text" maxlength="1" class="code-input" data-index="5" readonly>
                </div>

                <input type="hidden" name="code" id="codeInput">

                @error('code')
                    <span class="error-message">{{ $message }}</span>
                @enderror

                <button type="submit" class="submit-btn" id="submitBtn" disabled>Xác thực</button>
            </form>

            <div class="resend-section">
                <p class="resend-text">Không nhận được mã?</p>
                <form action="{{ route('password.email') }}" method="POST" style="display: inline;">
                    @csrf
                    <input type="hidden" name="email" value="{{ session('email') }}">
                    <button type="submit" class="resend-btn">Gửi lại mã</button>
                </form>
            </div>
        </div>
        <div class="overlay-panel">
            <div class="icon-wrapper">
                <img src="{{ asset('images/mail.png') }}" alt="Email Icon">
            </div>
            <h1>Xác thực tài khoản</h1>
            <p>Làm theo các bước sau để hoàn tất:</p>
            
            <div class="steps">
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-text">Kiểm tra email của bạn</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-text">Nhập mã 6 số vào form</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-text">Tạo mật khẩu mới</div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const inputs = document.querySelectorAll('.code-input');
        const codeInput = document.getElementById('codeInput');
        const submitBtn = document.getElementById('submitBtn');
        const verifyForm = document.getElementById('verifyForm');

        let currentIndex = 0;
        let code = Array(6).fill('');

        inputs[0].focus();

        const updateCode = () => {
            const fullCode = code.join('');
            codeInput.value = fullCode;
            submitBtn.disabled = fullCode.length !== 6;
        };

        document.addEventListener('keydown', e => {
            const active = document.activeElement;
            if (!active.classList.contains('code-input')) return;

            const key = e.key;

            if (/^\d$/.test(key)) {
                e.preventDefault();
                code[currentIndex] = key;
                inputs[currentIndex].value = key;
                if (currentIndex < 5) currentIndex++;
                inputs[currentIndex].focus();
            } else if (key === 'Backspace') {
                e.preventDefault();
                code[currentIndex] = '';
                inputs[currentIndex].value = '';
                if (currentIndex > 0) currentIndex--;
                inputs[currentIndex].focus();
            } else if (key === 'ArrowLeft' && currentIndex > 0) {
                e.preventDefault();
                currentIndex--;
                inputs[currentIndex].focus();
            } else if (key === 'ArrowRight' && currentIndex < 5) {
                e.preventDefault();
                currentIndex++;
                inputs[currentIndex].focus();
            }

            updateCode();
        });

        inputs.forEach((input, index) => {
            input.addEventListener('click', () => {
                currentIndex = index;
                input.focus();
            });

            input.addEventListener('paste', e => {
                e.preventDefault();
                const nums = e.clipboardData.getData('text').replace(/\D/g, '').slice(0, 6);
                if (!nums) return;

                nums.split('').forEach((n, i) => {
                    code[i] = n;
                    inputs[i].value = n;
                });

                currentIndex = Math.min(nums.length, 5);
                inputs[currentIndex].focus();
                updateCode();
            });
        });

        verifyForm.addEventListener('submit', e => {
            if (code.join('').length !== 6) {
                e.preventDefault();
                inputs.forEach(i => i.classList.add('error'));
            }
        });
    </script>
</body>
</html>