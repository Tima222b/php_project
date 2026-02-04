<?php
session_start();
$isAuth = isset($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <style>
        .container {
            max-width: 800px;
            margin: 100px auto;
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 0 20px rgba(0,0,0,.1);
        }

        .container h1 {
            margin-bottom: 20px;
        }

        .container p {
            font-size: 18px;
            color: #555;
            margin-bottom: 30px;
        }

        .buttons {
            display: flex;
            justify-content: center;
            gap: 20px;
        }

        .buttons a {
            display: inline-block;
            padding: 12px 25px;
            background: #0077ff;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            transition: .2s;
        }

        .buttons a.secondary {
            background: #555;
        }

        .buttons a:hover {
            opacity: .85;
        }
    </style>
</head>
<body>
    
    <div class="container">
    <h1>Добро пожаловать 👋</h1>

    <p>
        Это тестовый проект на <strong>нативном PHP + MySQL</strong><br>
        Регистрация, авторизация, профиль пользователя
    </p>

    <div class="buttons">
        <?php if ($isAuth): ?>
            <a href="profile.php">Перейти в профиль</a>
            <a href="logout.php" class="secondary">Выйти</a>
        <?php else: ?>
            <a href="login.php">Войти</a>
            <a href="register.php" class="secondary">Регистрация</a>
        <?php endif; ?>
    </div>
</div>








</body>
</html>