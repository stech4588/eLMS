<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Application Update</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { width: 90%; max-width: 600px; margin: 20px auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .reason-box { background-color: #f8f9fa; border-left: 4px solid #dc3545; padding: 15px; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Update on Your Instructor Application</h2>
        <p>Hello {{ $user->name }},</p>
        <p>Thank you for your interest in becoming an instructor with MBM Learning. After careful review, we regret to inform you that we are unable to approve your application at this time.</p>
        
        <div class="reason-box">
            <p><strong>Reason for decision:</strong></p>
            <p>{{ $reason }}</p>
        </div>

        <p>We receive many applications and have a limited number of new instructors we can accept at this time. We encourage you to continue developing your skills and content, and you are welcome to reapply in the future.</p>
        <p>We wish you the best in your teaching endeavors.</p>
        <p>Sincerely,<br>The MBM Learning Team</p>
    </div>
</body>
</html> 