<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - OBGAPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #F5F5DC;
            margin: 0;
            padding: 0;
            color: #333333;
        }

        .container {
            max-width: 600px;
            margin: 30px auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(51, 51, 51, 0.1);
            border-top: 10px solid #FFE09C;
        }

        h1 {
            background-color: #FFE09C;
            color: #333333;
            padding: 15px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        p {
            font-size: 16px;
            line-height: 1.6;
            color: #666666;
            margin-bottom: 20px;
        }

        .code {
            font-size: 26px;
            font-weight: bold;
            text-align: center;
            margin: 20px auto;
            padding: 15px 20px;
            background-color: #FFF6D1;
            border-left: 6px solid #FFE09C;
            border-radius: 6px;
            color: #333333;
            width: fit-content;
        }

        .button {
            display: inline-block;
            background-color: #FFE09C;
            color: #333333;
            padding: 12px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: bold;
            margin: 20px auto;
            display: block;
            text-align: center;
        }

        .note {
            font-size: 14px;
            color: #777777;
            text-align: center;
            margin-top: 20px;
        }

        .footer {
            text-align: center;
            font-size: 13px;
            color: #999999;
            margin-top: 40px;
            border-top: 1px solid #e0e0e0;
            padding-top: 20px;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Password Reset Request</h1>

        <p>Hello,</p>
        <p>We received a request to reset your password for your <strong>OBGAPP</strong> account. Please use the
            following verification code to proceed:</p>

        <div class="code">{{ $code }}</div>

        <!-- Optional action button -->
        <a class="button">Reset Password</a>

        <p class="note"><strong>Note:</strong> This code is valid for 1 hour only. If you did not request a password
            reset, you can safely ignore this email.</p>

        <div class="footer">
            <p>If you have any questions, feel free to contact our support team.</p>
            <p>&copy; 2023 OBGAPP. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
