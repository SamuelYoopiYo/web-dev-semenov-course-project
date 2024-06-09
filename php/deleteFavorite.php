<?php
session_start();
include "dbConnector.php";
$globalId = $_GET['id'];
$userId = $_SESSION['user']['id'];
//echo "DELETE FROM featured WHERE id_users_log =" . $userId . " AND  id_info_playground = " . $globalId . ";";
$favoriteGroundDelete = mysqli_query($mysql, "DELETE FROM featured WHERE id_users_log = " . $userId . " AND id_info_playground = " . $globalId);

if ($favoriteGroundDelete) {
    if ($_GET['source'] == 'index') {
        $_SESSION['message'] = 'Площадка успешно удалена из избранного.';
    }
    $favoriteGroundInfoQuery = mysqli_query($mysql, "SELECT global_id, ObjectName, CONCAT(District,' ', Address) as Address FROM featured JOIN info_playground WHERE id_info_playground = global_id AND id_users_log = " . $_SESSION['user']['id'] . ";");
    $favoriteGroundInfo = array();
    while ($row = mysqli_fetch_assoc($favoriteGroundInfoQuery)) {
        $favoriteGroundInfo[] = $row;
    }
    $_SESSION['user']['favoriteGrounds'] = $favoriteGroundInfo;
} else {

    $_SESSION['message'] = 'Ошибка при удалении площадки из избранного.';
}


if ($_GET['source'] == 'index') {
    header("Location: ../index.php");
} elseif ($_GET['source'] == 'profile') {
    header("Location: profile.php");
}elseif ($_GET['source'] == 'card') {
    header("Location: card.php?id=".$globalId."&source=".$_GET['presourse']);
}
