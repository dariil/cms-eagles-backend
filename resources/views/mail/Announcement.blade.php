<!DOCTYPE html>
<html>
<head>
    <title>Important Announcement</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background-image: url('https://i.imgur.com/f9IlbB7.jpeg'); /* Replace with actual URL */
            background-size: cover;
            background-position: center;
            padding: 20px;
            text-align: center;
        }
        .header img {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px;
        }
        .content p {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
        }
        .description {
            display: -webkit-box;
            -webkit-line-clamp: 4; /* number of lines to show */
            -webkit-box-orient: vertical;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .footer {
            background-color: #f1f1f1;
            padding: 10px;
            text-align: center;
            font-size: 14px;
            color: #777777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="https://i.imgur.com/IEBrrIY.png" alt="Logo">
            <h1>Announcement</h1>
        </div>
        <div class="content">
            <h2>A new announcement has been posted.</h2>
            <br />
            <p><strong>{{$emailData['announcement']->title}}</strong></p>
            <p><a href="http://localhost:5173/{{ $emailData['club_code'] }}/announcements/view_announcement/{{$emailData['announcement']->announcement_id}}">Click here</a> to view more details.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
