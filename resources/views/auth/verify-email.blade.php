<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 50px auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding: 20px 0;
        }
        .header img {
            width: 100px;
            margin-bottom: 10px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            color: #4caf50;
        }
        .content {
            text-align: center;
            line-height: 1.8;
        }
        .content a {
            text-decoration: none;
            background-color: #4caf50;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        .content a:hover {
            background-color: #45a049;
        }
        .footer {
            margin-top: 20px;
            font-size: 12px;
            text-align: center;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h1>Verify Your Email</h1>
        </div>
        <div class="content">
            <p>Hi {{ $user->name }},</p>
            <p>Thank you for signing up. Please click the button below to verify your email address.</p>
            <a href="{{ $url }}">Verify Email</a>
            <p>If you did not create an account, no further action is required.</p>
        </div>
        <div class="footer">
            <p>© {{ date('Y') }} Your Application. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
