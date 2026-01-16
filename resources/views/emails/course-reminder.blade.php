<!DOCTYPE html>
<html>
<head>
    <title>Course Reminder</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; color: #333; margin: 0; padding: 0; }
        .container { width: 100%; max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .header { text-align: center; padding-bottom: 20px; border-bottom: 1px solid #eee; }
        .header img { max-width: 150px; }
        .content { padding: 20px 0; }
        .content h1 { font-size: 24px; color: #333; }
        .content p { font-size: 16px; line-height: 1.5; }
        .course-list { list-style: none; padding: 0; }
        .course-list li { background-color: #f9f9f9; margin-bottom: 10px; padding: 15px; border-left: 5px solid #007bff; border-radius: 4px; }
        .course-list li a { text-decoration: none; color: #007bff; font-weight: bold; }
        .footer { text-align: center; padding-top: 20px; border-top: 1px solid #eee; font-size: 14px; color: #777; }
        .button { display: inline-block; background-color: #007bff; color: #ffffff; padding: 10px 20px; border-radius: 5px; text-decoration: none; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('/images/MBM_Uni.png') }}" alt="ElevateU University Logo">
            <h1>Hello {{ $user->name }},</h1>
        </div>
        <div class="content">
            <p>We noticed you haven't completed some of your courses at ElevateU University. Keep up the great work and finish strong!</p>
            <p>Here are the courses you're still working on:</p>
            <ul class="course-list">
                @foreach ($courses as $course)
                    <li><a href="{{ url('/courses/' . $course->id) }}">{{ $course->title }}</a></li>
                @endforeach
            </ul>
            <p>Click the button below to continue your learning journey!</p>
            <p style="text-align: center;">
                <a href="{{ url('/dashboard') }}" class="button">Go to Dashboard</a>
            </p>
            <p>If you have any questions, feel free to contact our support team.</p>
            <p>Happy learning!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} ElevateU University. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
