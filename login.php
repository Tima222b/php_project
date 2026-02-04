<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>



<?php
session_start();
require 'db.php';
$errors=[];

if ($_SERVER['REQUEST_METHOD']==='POST') {
    $login = trim($_POST['login']);
    $password = $_POST['password'];
    $captcha_token = $_POST['smart-captcha-token'] ?? '';

    
    $secret = '';
    $verify = file_get_contents("https://captcha-api.yandex.com/verify?secret=$secret&token=$captcha_token");
    $verify = json_decode($verify,true);

    if (!$verify['success']) $errors[]="Ошибка CAPTCHA";
    else {
        $stmt=$pdo->prepare("SELECT * FROM users WHERE email=? OR phone=?");
        $stmt->execute([$login,$login]);
        $user = $stmt->fetch();
        if ($user && password_verify($password,$user['password'])) {
            $_SESSION['user_id']=$user['id'];
            header('Location: profile.php');
            exit;
        } else $errors[]="Неверный логин или пароль";
    }
}
?>

<link rel="stylesheet" href="assets/css/style.css">
<h2>Вход</h2>
<form method="POST">
    <input type="text" name="login" placeholder="Email или телефон" required>
    <input type="password" name="password" placeholder="Пароль" required>
    <div class="smart-captcha"></div>
    <input type="hidden" name="smart-captcha-token">
    <button type="submit">Войти</button>
</form>

<script src="assets/js/smartcaptcha.js"></script>
<script>
initSmartCaptcha('', function(token){
    document.querySelector('input[name="smart-captcha-token"]').value = token;
});
</script>

<?php foreach($errors as $e) echo "<p class='error'>$e</p>"; ?>

    
</body>
</html>