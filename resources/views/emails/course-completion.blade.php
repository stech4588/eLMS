<!DOCTYPE html>
<html>
<head>
    <title>Course Completion Certificate</title>
</head>
<body>
    <img src="{{ $message->embed(public_path('images/MBM_Uni.png')) }}" alt="ElevateU University Logo" style="width: 150px;">
    <h1>Congratulations, {{ $userName }}!</h1>
    <p>We are thrilled to inform you that you have successfully completed the course: <strong>{{ $courseTitle }}</strong>.</p>
    <p>Your hard work and dedication have paid off. Keep up the great work!</p>

    <hr>
    <h2>Invite a friend, help them start learning!</h2>
    <p>Share your referral link below. When your friend signs up, they'll start their journey too:</p>
    <p>
        <a href="{{ $referralUrl }}" style="display:inline-block;padding:10px 16px;background:#1a73e8;color:#fff;text-decoration:none;border-radius:6px;">
            Invite with my referral link
        </a>
    </p>
    <p style="word-break: break-all; color:#555;">Or copy this link: {{ $referralUrl }}</p>
    <p>Best regards,</p>
    <p>The ElevateU University Team</p>
</body>
</html>
