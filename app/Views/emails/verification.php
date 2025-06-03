<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .email-wrapper {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            padding: 40px;
            margin: 20px 0;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #0d6efd;
            margin-bottom: 20px;
        }
        .title {
            color: #212529;
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
        }
        .content {
            color: #6c757d;
            font-size: 16px;
            margin-bottom: 30px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #0d6efd;
            color: #ffffff !important;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 500;
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            color: #6c757d;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
        }
        .warning {
            background-color: #fff3cd;
            color: #856404;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="email-wrapper">
            <div class="header">
                <div class="logo">BlogHub</div>
                <h1 class="title">Verify Your Email Address</h1>
            </div>

            <div class="content">
                <p>Thank you for registering with BlogHub! To complete your registration and start using your account, please verify your email address by clicking the button below:</p>
            </div>

            <div style="text-align: center;">
                <a href="<?= $verificationLink ?>" class="button">Verify Email Address</a>
            </div>

            <div class="warning">
                <strong>Note:</strong> This verification link will expire in 24 hours. If you did not create an account, you can safely ignore this email.
            </div>

            <div class="footer">
                <p>If you're having trouble clicking the button, copy and paste this URL into your web browser:</p>
                <p style="word-break: break-all; color: #0d6efd;"><?= $verificationLink ?></p>
                <p style="margin-top: 20px;">© <?= date('Y') ?> BlogHub. All rights reserved.</p>
            </div>
        </div>
    </div>
</body>
</html> 