<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Instructor Application</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .button { display: inline-block; padding: 10px 20px; margin: 20px 0; background-color: #91c3f9; color: #ffffff; text-decoration: none; border-radius: 5px; }
        .button:hover { background-color: #65afff; }
        p { margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <h2>New Instructor Application</h2>
        <p>Hello Admin,</p>
        <p>A new instructor, <strong>{{ $user->name }}</strong> ({{ $user->email }}), has registered and is waiting for your review.</p>
        <p>Please review their application and approve or reject it at your earliest convenience.</p>
        <a href="{{ route('admin.instructors.index') }}" class="button">View Instructor Applications</a>
        <p>Thank you,<br>The MBM Learning Team</p>
    </div>
</body>
</html> 