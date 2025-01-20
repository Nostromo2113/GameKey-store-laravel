<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verification Successful</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f4f8;
            margin: 0;
            padding: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }
        .container {
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            width: 100%;
            padding: 20px;
            text-align: center;
        }
        header {
            margin-bottom: 20px;
        }
        h1 {
            color: #333;
            font-size: 24px;
            margin: 0;
        }
        footer {
            margin-top: 20px;
            color: #888;
            font-size: 14px;
        }
        .success {
            color: #28a745;
            font-size: 20px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <header>
        <h1>Verification Complete</h1>
    </header>
    <main>
        <p class="success">Your email has been successfully verified!</p>
    </main>
    <footer>
        Laravel v{{ Illuminate\Foundation\Application::VERSION }} (PHP v{{ PHP_VERSION }})
    </footer>
</div>
</body>
</html>
