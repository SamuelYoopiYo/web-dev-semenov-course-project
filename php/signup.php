<?php
session_start();
include 'dbConnector.php';

$name = $_POST['name'];
$surname = $_POST['surname'];
$phone = $_POST['phone'];
$login = $_POST['login'];
$password = md5($_POST['password']);
$password_repit = md5($_POST['password_repit']);

// if($password)
if ($password === $password_repit) {
    $checkerQuery = mysqli_query($mysql, "SELECT * FROM user_log WHERE login = '" . $login . "' AND password = '" . $password . "';");
    if (mysqli_num_rows($checkerQuery) != 0) {
        $_SESSION['message'] = 'Такой пользователь уже существует';
        header('Location: regist.php');
    } else {
        $checkerQuery =  mysqli_query($mysql, "INSERT INTO `user_info` ( `name`,`surname`,`phone`) VALUES ('" . $name . "', '" . $surname . "', '" . $phone . "');");
        if (!$checkerQuery) {
            $_SESSION['message'] = 'Упс.. ошибка, попробуйте еще раз';
            header('Location: regist.php');
        } else {
            $idNewUser = mysqli_insert_id($mysql);
            $checkerQuery = mysqli_query($mysql, "INSERT INTO `user_log` (`login`, `password`,`id_user_info`) VALUES ('" . $login . "', '" . $password . "', '" . $idNewUser . "');");
            if (!$checkerQuery) {
                $_SESSION['message'] = 'Упс.. ошибка, попробуйте еще раз';
                header('Location: regist.php');
            } else {
                $_SESSION['message'] = 'Вы зарегестрированы';
                header('Location: autoriz.php');
            }
        }
    }
} else {
    $_SESSION['message'] = 'Пароли не совпадают';
    header('Location: regist.php');
}
