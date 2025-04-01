<!DOCTYPE html>
<html>
<head>
    <title>GameShop - Ваши ключи активации</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h1 {
            color: #2c3e50;
            font-size: 24px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            margin-top: 0;
        }
        ul {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        li {
            background: #fff;
            margin-bottom: 8px;
            padding: 12px 15px;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.05);
            font-family: monospace;
            font-size: 16px;
            color: #2980b9;
            border-left: 3px solid #3498db;
        }
        p.footer {
            color: #7f8c8d;
            font-size: 14px;
            margin-top: 30px;
            padding-top: 15px;
            border-top: 1px solid #eee;
        }
    </style>
</head>
<body>
<h1>Ваши ключи активации</h1>

<ul>
    @foreach($activationKeys as $key)
        <li>{{ $key }}</li>
    @endforeach
</ul>

<p class="footer">Если вы не запрашивали это письмо, пожалуйста, проигнорируйте его.</p>
</body>
</html>
