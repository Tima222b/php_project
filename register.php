<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Document</title>
</head>
<body>
    


<?php
session_start();
require 'db.php';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) $errors[] = "Пароли не совпадают.";

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email=? OR phone=?");
    $stmt->execute([$email, $phone]);
    if ($stmt->fetch()) $errors[] = "Email или телефон уже зарегистрированы.";

    if (!$errors) {
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (name,email,phone,password) VALUES (?,?,?,?)");
        $stmt->execute([$name,$email,$phone,$hash]);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        header('Location: profile.php');
        exit;
    }
}
?>


<h2 style=text-align:center;>Регистрация</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Имя" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="phone" placeholder="Телефон" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <input type="password" name="confirm_password" placeholder="Повтор пароля" required>
    <button type="submit">Зарегистрироваться</button>
</form>
<?php foreach($errors as $e) echo "<p class='error'>$e</p>"; ?>




</body>
</html>