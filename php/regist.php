<?php
session_start();
if ($_SESSION['user']) {
    header('Location: profile.php');
}
$pattern_pass = "[0-9a-zA-Z!@.]{6,30}";
$pattern_phone = "+[0-9]{12}";
$pattern_name = "[а-яА-Я]{1,30}";
$pattern_surname = "[а-яА-Я]{1,30}";
$pattern_login = "[a-zA-Z!@.]{6,30}";

?>

<!doctype html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="img/favicon.ico">
    <meta charset="UTF-8">
    <title>Авторизация и регистрация</title>
    <link rel="stylesheet" href="../style/regist.css">
</head>

<body>


    <form action="signup.php" method="post" enctype="multipart/form-data">
        <label>Имя</label>
        <input type="text" name="name" pattern='<?= $pattern_name ?>'placeholder="Введите имя" tabindex="1" required>
        <label>Фамилия</label>
        <input type="text" name="surname" pattern='<?= $pattern_surname ?>' placeholder="Введите фамилию" tabindex="2" required>
        <label>Телефон</label>
        <input type="tel" name="phone" pattern='<?= $pattern_phone ?>' placeholder="+7 111 222 33 44" tabindex="3" value="+7" required>
        <label>Логин</label>
        <input type="text" name="login" pattern='<?= $pattern_login ?>' placeholder="Введите логин" tabindex="4" required>
        <label>Пароль</label>
        <input type="password" name="password" pattern='<?= $pattern_pass ?>' placeholder="Введите пароль" tabindex="5" required>
        <label>Подтверждение пароля</label>
        <input type="password" name="password_repit" pattern="<?= $pattern_pass ?>" placeholder="Подтвердите пароль" tabindex="6" required>
        <button type="submit">Зарегистрироваться</button>
        <p class="btn-transition">
            <a href="autoriz.php" id="right-btn">Войти</a>
            <a href="../index.php" id="left-btn">Назад</a>
        </p>
        <?php
        if ($_SESSION['message']) {
            echo '<p class="msg"> ' . $_SESSION['message'] . ' </p>';
        }
        unset($_SESSION['message']);
        ?>
    </form>

</body>

</html>