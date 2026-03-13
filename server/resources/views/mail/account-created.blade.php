<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Account Created</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }
        .email-container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #9b4a7f; /* Change this to the desired color */
            color: #ffffff;
            padding: 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .content {
            padding: 20px;
            line-height: 1.6;
        }
        .content p {
            margin: 15px 0;
        }
        .content a {
            color: #9b4a7f;
            text-decoration: none;
        }
        .footer {
            background-color: #f4f4f4;
            color: #777;
            text-align: center;
            padding: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <h1>Добре дошли!</h1>
        </div>
        <div class="content">
            <p>Здравей {{ $name }},</p>
            <p>Радваме се, че сте тук! Вашето потребителско име е <strong>{{ $name }}</strong>. Можете да го промените от <a href="{{ route('user.profile') }}">профила си</a> по всяко време.</p>
            <p>Благодарим ви, че избрахте нас!</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Всички права запазени.</p>
        </div>
    </div>
</body>
</html>
