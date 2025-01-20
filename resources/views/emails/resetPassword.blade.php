<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Our Service</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            color: #333;
            margin: 0;
            padding: 0;
            border-radius: 4px;
        }
        .container {
            width: 100%;
            max-width: 600px;
            background-color: #c9cdde;
            margin: 0 auto;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 5px 1px black;
        }
        .password {
            color: white;
            background: black;
            font-weight: bold;
            padding: 10px;
            border-radius: 5px;
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
        <h1>Вы отправили заявку на восстановление пароля</h1>
    </header>
    <main>
        <p>Ваш новый пароль:   <span class="password"><strong>{{ $password }}</strong></span> </p>
        <p>Теперь вы можете авторизоваться. В личном кабинете можно сменить пароль на собственный</p>
        <p>Рекомендуем удалить данное письмо</p>
    </main>
    <footer>
        Отличного дня.<br>
        &copy; {{ date('Y') }} E-shop тестовый проект
    </footer>
</div>
</body>
</html>
