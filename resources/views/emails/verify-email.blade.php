<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verify Your Email Address - OBGAPP</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #F5F5DC;
            margin: 0;
            padding: 0;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 40px 30px;
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.08);
            border-top: 8px solid #FFE09C;
        }

        .logo {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo img {
            height: 50px;
        }

        h1 {
            font-size: 24px;
            text-align: center;
            background-color: #FFE09C;
            padding: 15px;
            border-radius: 8px;
            margin: 0 auto 30px auto;
            color: #333333;
        }

        p {
            font-size: 16px;
            line-height: 1.7;
            color: #666666;
            margin-bottom: 20px;
        }

        .code {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            padding: 16px 30px;
            background-color: #FFF6D1;
            color: #333333;
            border: 2px dashed #FFE09C;
            border-radius: 8px;
            width: max-content;
            margin: 30px auto;
            letter-spacing: 2px;
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 14px;
            color: #999999;
            border-top: 1px solid #eee;
            padding-top: 20px;
        }

        .app-name {
            font-weight: bold;
            color: #333333;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Logo placeholder -->
        <div class="logo">
            <img src="https://via.placeholder.com/120x40?text=OBGAPP" alt="OBGAPP Logo" />
        </div>

        <h1>Email Verification</h1>

        <p>Hello {{ $user['email'] }},</p>

        <p>Thank you for registering with <span class="app-name">OBGAPP</span>.</p>
        <p>Please use the verification code below to complete your registration:</p>

        <div class="code">{{ $code }}</div>

        <p>This code is valid for a limited time. If you did not request this, please ignore this email.</p>

        <div class="footer">
            <p>If you need help, please contact our support team at support@obgapp.com.</p>
            <p>&copy; 2025 OBGAPP. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
