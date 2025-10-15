<!DOCTYPE html>
<html>
<head>
    <title>Course Completion Certificate</title>
</head>
<body>
    <img src="{{ $message->embed(public_path('images/MBM_Uni.png')) }}" alt="MBM University Logo" style="width: 150px;">
    <h1>Congratulations, {{ $userName }}!</h1>
    <p>We are thrilled to inform you that you have successfully completed the course: <strong>{{ $courseTitle }}</strong>.</p>
    <p>Your hard work and dedication have paid off. Keep up the great work!</p>
    <p>Best regards,</p>
    <p>The MBM University Team</p>
</body>
</html>
