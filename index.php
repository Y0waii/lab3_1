<?php
require 'vendor/autoload.php';

session_start();

$action = $_GET['action'] ?? 'login';
$error = '';
$success = '';

if ($_POST) {
    if ($action === 'register') {
        if (App\User::register($_POST['login'], $_POST['password'], $_POST['bg_color'], $_POST['font_color'])) {
            $success = 'Регистрация успешна!';
            $action = 'login';
        } else {
            $error = 'Ошибка регистрации';
        }
    } elseif ($action === 'auth') {
        $user = App\User::login($_POST['login'], $_POST['password']);
        if ($user) {
            $_SESSION['user'] = $user;
            $action = 'profile';
        } else {
            $error = 'Неверный логин/пароль';
        }
    }
}

$bg_color = '#ffffff';
$font_color = '#000000';
if (isset($_SESSION['user'])) {
    $settings = App\User::getUserSettings($_SESSION['user']['id']);
    $bg_color = $settings['bg_color'];
    $font_color = $settings['font_color'];
}
?>

<!DOCTYPE html>
<html>
<head><title>Lab 3.1</title></head>
<body style="background:<?= $bg_color ?>; color:<?= $font_color ?>; padding: 20px;">
    <?php if ($error): ?><p style="color:red;"><?= $error ?></p><?php endif; ?>
    <?php if ($success): ?><p style="color:green;"><?= $success ?></p><?php endif; ?>
    
    <?php if (!isset($_SESSION['user'])): ?>
        <?php if ($action === 'register'): ?>
            <h2>Регистрация</h2>
            <form method="POST">
                <input name="login" placeholder="Логин" required><br><br>
                <input type="password" name="password" placeholder="Пароль" required><br><br>
                <label>Цвет фона: <input name="bg_color" type="color" value="#ffffff" required></label><br><br>
                <label>Цвет шрифта: <input name="font_color" type="color" value="#000000" required></label><br><br>
                <button type="submit">Зарегистрироваться</button>
                <a href="?action=login">Войти</a>
            </form>
        <?php else: ?>
            <h2>Авторизация</h2>
            <form method="POST">
                <input name="login" placeholder="Логин" required><br><br>
                <input type="password" name="password" placeholder="Пароль" required><br><br>
                <button type="submit">Войти</button>
                <a href="?action=register">Регистрация</a>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <h2>Профиль: <?= $_SESSION['user']['login'] ?></h2>
        <p>Фон: <?= $bg_color ?> | Шрифт: <?= $font_color ?></p>
        <a href="?logout=1">Выйти</a>
    <?php endif; ?>
    
    <?php if (isset($_GET['logout'])) { session_destroy(); header('Location: ?'); } ?>
</body>
</html>
