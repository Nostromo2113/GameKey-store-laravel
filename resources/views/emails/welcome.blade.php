<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin-top: 0;
        }
        p {
            line-height: 1.6;
        }
        footer {
            margin-top: 20px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Welcome, {{ $user->name }}!</h1>
    </header>
    <main>
        <p>Thank you for registering with us. Your account has been created successfully.</p>
        <p><strong>Your password:</strong> {{ $password }}</p>
        <p>For security reasons, we recommend you change your password after logging in.</p>
    </main>
    <footer>
        Thank you for choosing our service.<br>
        &copy; {{ date('Y') }} Your Company Name
    </footer>
</div>
</body>
</html>
