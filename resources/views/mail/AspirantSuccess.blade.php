<!DOCTYPE html>
<html>
<head>
    <title>Application Submitted Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #ab3732;
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
            /* background-color: linear-gradient(to top, #1E1E1E 20%, #ab3732 150%);    */
            /* opacity: 0.90; */
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
            color: #c1c1c1;
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
        .content ul {
            padding-left: 20px;
            margin: 0;
        }
        .content ul li {
            font-size: 16px;
            line-height: 1.5;
            color: #333333;
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
            <h1>Application Submitted Successfully</h1>
        </div>
        <div class="content">
            <p>Dear {{ $emailData['application']->firstname }} {{ $emailData['application']->lastname }},</p>
            <p>Thank you for submitting your application. We have received the following details:</p>
            <ul>
                <li>First Name: {{ $emailData['application']->firstname }}</li>
                <li>Middle Name: {{ $emailData['application']->middlename }}</li>
                <li>Last Name: {{ $emailData['application']->lastname }}</li>
                <li>Email: {{ $emailData['application']->email }}</li>
                <li>Number: {{ $emailData['application']->number }}</li>
                <li>Club Name: {{ $emailData['club_name'] }}</li>
            </ul>
            <p>We will review your application and get back to you shortly.</p>
            <p>Best regards,</p>
            <p>{{ config('app.name') }}</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </div>
</body>
</html>
