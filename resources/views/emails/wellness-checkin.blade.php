<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wellness Check-in</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
        }
        .header img {
            max-width: 150px;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            text-align: center;
            font-size: 0.8em;
            color: #777;
            padding-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Company Logo">
        </div>
        <div class="content">
            <p>Hello {{ $userName }},</p>
            <p>{{ $motivationalMessage }}</p>
            <p>We're here to support you on your learning journey. Keep going, and remember why you started!</p>
            <p>Best regards,<br>The eLMS Team</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} eLMS. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
