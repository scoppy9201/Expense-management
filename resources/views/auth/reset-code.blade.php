<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mã xác thực đặt lại mật khẩu</title>
        <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }

        .email-wrapper {
            width: 100%;
            padding: 40px 20px;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        }

        .email-header {
            background: linear-gradient(135deg, #4a90e2 0%, #2a5298 100%);
            padding: 40px 30px;
            text-align: center;
            color: #ffffff;
        }

        .email-header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 700;
        }

        .email-body {
            padding: 40px 30px;
            color: #333333;
            font-size: 16px;
            line-height: 1.6;
        }

        .email-body p {
            margin: 0 0 20px;
        }

        .code-box {
            margin: 30px 0;
            text-align: center;
        }

        .code-box-inner {
            display: inline-block;
            background: linear-gradient(135deg, #e7f3ff 0%, #f0f7ff 100%);
            border: 2px dashed #4a90e2;
            border-radius: 15px;
            padding: 30px;
        }

        .code-box-inner p {
            margin: 0 0 10px;
            color: #666666;
            font-size: 14px;
            font-weight: 600;
        }

        .code-box-inner h2 {
            margin: 0;
            color: #2a5298;
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 8px;
        }

        .warning-box {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin: 25px 0;
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }

        .warning-box img {
            width: 20px;
            height: 20px;
        }

        .warning-box p {
            margin: 0;
            color: #856404;
            font-size: 14px;
            line-height: 1.6;
        }

        .signature {
            margin-top: 25px;
            color: #666666;
            font-size: 15px;
            line-height: 1.6;
        }

        .signature strong {
            color: #2a5298;
        }

        .email-footer {
            background-color: #f8f9fa;
            padding: 25px 30px;
            text-align: center;
            border-top: 1px solid #e0e0e0;
            color: #999999;
            font-size: 12px;
        }

        .email-footer p {
            margin: 0 0 10px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">

            <!-- Header -->
            <div class="email-header">
                <h1> Mã xác thực đặt lại mật khẩu</h1>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p>Xin chào,</p>
                <p>Chúng tôi đã nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn. Sử dụng mã xác thực bên dưới để tiếp tục:</p>

                <!-- Code Box -->
                <div class="code-box">
                    <div class="code-box-inner">
                        <p>MÃ XÁC THỰC CỦA BẠN</p>
                        <h2>{{ $code }}</h2>
                    </div>
                </div>

                <!-- Warning Box -->
                <div class="warning-box">
                    <img src="/images/crisis.png" alt="Cảnh báo">
                    <p>
                        <strong>Lưu ý quan trọng:</strong><br>
                        • Mã này sẽ hết hiệu lực sau <strong>3 phút</strong><br>
                        • Không chia sẻ mã này với bất kỳ ai<br>
                        • Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua email này
                    </p>
                </div>

                <!-- Signature -->
                <div class="signature">
                    Trân trọng,<br>
                    <strong>Đội ngũ Quản lý hệ thống</strong>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>Email này được gửi tự động, vui lòng không trả lời.</p>
                <p>© 2025 Quản lý Chi tiêu. All rights reserved.</p>
            </div>

        </div>
    </div>
</body>
</html>