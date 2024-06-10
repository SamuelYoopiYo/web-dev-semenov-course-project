<?php
session_start();
include 'dbConnector.php';

$login = $_POST['login'];
$password = md5($_POST['password']);
//echo "SELECT * FROM `user_log` WHERE `login` = '$login' AND `password` = '$password'";

$check_user = mysqli_query($mysql, "SELECT * FROM `user_log` WHERE `login` = '$login' AND `password` = '$password'");

if (mysqli_num_rows($check_user) == 1) {

    $user_log_info = mysqli_fetch_assoc($check_user);
    $user_info = mysqli_query($mysql, "SELECT * FROM `user_info` WHERE `id` = " . $user_log_info['id_user_info'] . ";");
    $user = mysqli_fetch_assoc($user_info);

    $favoriteGroundInfoQuery = mysqli_query($mysql, "SELECT global_id, ObjectName, CONCAT(District,' ', Address) as Address, `geoData`  FROM featured JOIN info_playground WHERE id_info_playground = global_id AND id_users_log = " . $user_log_info['id'] . ";");    
    $favoriteGroundInfo = array();
    while ($row = mysqli_fetch_assoc($favoriteGroundInfoQuery)) {
        $favoriteGroundInfo[] = $row;
    }


    $_SESSION['user'] = [
        "id" => $user_log_info['id'],
        "name" => $user['name'],
        "surname" => $user['surname'],
        "phone" => $user['phone'],
        "favoriteGrounds" => $favoriteGroundInfo
    ];
    

    //$favoriteGroundInfoQuery = mysqli_query($mysql, "SELECT `global_id`, `ObjectName`, CONCAT(`District`,' ', `Address`) as Address FROM featured JOIN info_playground WHERE id_info_playground = global_id AND id_users_log = " . $_SESSION['user']['id'] . ";");


    header('Location: profile.php');
} else {
    $_SESSION['message'] = 'Пароль и логин неверные';
    header('Location: autoriz.php');
}
