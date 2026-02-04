<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>


    <?php
session_start();
require 'db.php';
if (!isset($_SESSION['user_id'])) header('Location: login.php');

$stmt=$pdo->prepare("SELECT * FROM users WHERE id=?");
$stmt->execute([$_SESSION['user_id']]);
$user=$stmt->fetch();

$errors=[]; $success='';
if ($_SERVER['REQUEST_METHOD']==='POST') {
    $name=trim($_POST['name']);
    $email=trim($_POST['email']);
    $phone=trim($_POST['phone']);
    $password=$_POST['password'];
    $confirm=$_POST['confirm_password'];

    if($password && $password!=$confirm) $errors[]="Пароли не совпадают";

    $stmt=$pdo->prepare("SELECT id FROM users WHERE (email=? OR phone=?) AND id!=?");
    $stmt->execute([$email,$phone,$user['id']]);
    if($stmt->fetch()) $errors[]="Email или телефон уже используются";

    if(!$errors){
        $query="UPDATE users SET name=?,email=?,phone=?";
        $params=[$name,$email,$phone];
        if($password){
            $query.=",password=?";
            $params[]=password_hash($password,PASSWORD_DEFAULT);
        }
        $query.=" WHERE id=?";
        $params[]=$user['id'];
        $stmt=$pdo->prepare($query);
        $stmt->execute($params);
        $success="Данные обновлены";
        $stmt=$pdo->prepare("SELECT * FROM users WHERE id=?");
        $stmt->execute([$user['id']]);
        $user=$stmt->fetch();
    }
}
?>


<h2>Профиль</h2>
<form method="POST">
    <input type="text" name="name" value="<?=htmlspecialchars($user['name'])?>" required>
    <input type="email" name="email" value="<?=htmlspecialchars($user['email'])?>" required>
    <input type="text" name="phone" value="<?=htmlspecialchars($user['phone'])?>" required>
    <input type="password" name="password" placeholder="Новый пароль">
    <input type="password" name="confirm_password" placeholder="Повтор пароля">
    <button type="submit">Обновить</button>
</form>

<?php
foreach($errors as $e) echo "<p class='error'>$e</p>";
if($success) echo "<p class='success'>$success</p>";
?>
<a href="logout.php">Выйти</a>

    
</body>
</html>