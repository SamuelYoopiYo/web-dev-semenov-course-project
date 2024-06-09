<?php
session_start();
include "dbConnector.php";
$globalId = $_GET['id'];
$userId = $_SESSION['user']['id'];
$favoriteGroundAdd = mysqli_query($mysql, "INSERT INTO featured (id_users_log, id_info_playground) VALUES (" . $userId . ", " . $globalId . ");");

if ($favoriteGroundAdd) {
    $_SESSION['message'] = 'Площадка успешно добавлена в избранное.';
    $favoriteGroundInfoQuery = mysqli_query($mysql, "SELECT global_id, ObjectName, CONCAT(District,' ', Address) as Address FROM featured JOIN info_playground WHERE id_info_playground = global_id AND id_users_log = " . $_SESSION['user']['id'] . ";");
    $favoriteGroundInfo = array();
    while ($row = mysqli_fetch_assoc($favoriteGroundInfoQuery)) {
        $favoriteGroundInfo[] = $row;
    }
    $_SESSION['user']['favoriteGrounds'] = $favoriteGroundInfo;
} else {
    $_SESSION['message'] = 'Ошибка при добавлении площадки в избранное.';
}

if ($_GET['source'] == 'index') {
    header("Location: ../index.php");
} elseif ($_GET['source'] == 'profile') {
    header("Location: profile.php");
} elseif ($_GET['source'] == 'card') {
    header("Location: card.php?id=".$globalId."&source=".$_GET['presourse']);
}
