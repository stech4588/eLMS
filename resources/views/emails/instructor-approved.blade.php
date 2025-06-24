<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Approved</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .button { display: inline-block; padding: 10px 20px; margin: 20px 0; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; }
        .button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Congratulations, {{ $user->name }}!</h2>
        <p>We are pleased to inform you that your instructor application for MBM Learning has been approved.</p>
        <p>You can now log in to your account and start creating your courses. We're excited to see what you'll share with our community of learners.</p>
        <a href="{{ route('login') }}" class="button">Log In to Your Account</a>
        <p>Welcome aboard,<br>The MBM Learning Team</p>
    </div>
</body>
</html> 