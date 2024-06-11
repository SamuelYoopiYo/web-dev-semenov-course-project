<?php
session_start();
include 'php/dbConnector.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//$result = mysqli_query($mysql, "SELECT `global_id`, `ObjectName`, CONCAT(`District`,' ', `Address`) as `Address`, `geoData` FROM `info_playground`;");
$result = mysqli_query($mysql, "SELECT * FROM `info_playground`;");
if (!$result) {
    echo '<p>Не удалось получить данные с БД </p>';
}

$arr_res = array();
$filterVariant = array(
    'HasEquipmentRental' => array(),
    'HasDressingRoom' => array(),
    'HasEatery' => array(),
    'HasToilet' => array(),
    'HasWifi' => array(),
    'HasFirstAidPost' => array(),
    'Lighting' => array(),
    'DisabilityFriendly' => array(),
    'Paid' => array(),
    'Seats' => array(),
    'SurfaceTypeWinter' => array(),
    'HasTechService' => array()
);

//Отбор уникальных значений для фильтров
while ($row = mysqli_fetch_assoc($result)) {
    $arr_res[] = $row;

    if (!in_array($row['HasEquipmentRental'], $filterVariant['HasEquipmentRental'])) {
        $filterVariant['HasEquipmentRental'][] = $row['HasEquipmentRental'];
    }
    if (!in_array($row['HasDressingRoom'], $filterVariant['HasDressingRoom'])) {
        $filterVariant['HasDressingRoom'][] = $row['HasDressingRoom'];
    }
    if (!in_array($row['HasEatery'], $filterVariant['HasEatery'])) {
        $filterVariant['HasEatery'][] = $row['HasEatery'];
    }
    if (!in_array($row['HasToilet'], $filterVariant['HasToilet'])) {
        $filterVariant['HasToilet'][] = $row['HasToilet'];
    }
    if (!in_array($row['HasWifi'], $filterVariant['HasWifi'])) {
        $filterVariant['HasWifi'][] = $row['HasWifi'];
    }
    if (!in_array($row['HasFirstAidPost'], $filterVariant['HasFirstAidPost'])) {
        $filterVariant['HasFirstAidPost'][] = $row['HasFirstAidPost'];
    }
    if (!in_array($row['Lighting'], $filterVariant['Lighting'])) {
        $filterVariant['Lighting'][] = $row['Lighting'];
    }
    if (!in_array($row['DisabilityFriendly'], $filterVariant['DisabilityFriendly'])) {
        $filterVariant['DisabilityFriendly'][] = $row['DisabilityFriendly'];
    }
    if (!in_array($row['Paid'], $filterVariant['Paid'])) {
        $filterVariant['Paid'][] = $row['Paid'];
    }
    if (!in_array($row['Seats'], $filterVariant['Seats'])) {
        $filterVariant['Seats'][] = $row['Seats'];
    }
    if (!in_array($row['SurfaceTypeWinter'], $filterVariant['SurfaceTypeWinter'])) {
        $filterVariant['SurfaceTypeWinter'][] = $row['SurfaceTypeWinter'];
    }
    if (!in_array($row['HasTechService'], $filterVariant['HasTechService'])) {
        $filterVariant['HasTechService'][] = $row['HasTechService'];
    }
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$entriesPerPage = 50;
$countPage = ceil(count($arr_res) / $entriesPerPage);
$subarr_res = array_slice($arr_res, ($page - 1) * $entriesPerPage, $entriesPerPage);

//Поиск по адресам
if (isset($_POST['address']) && $_POST['address'] !== "") {
    $res_search = array();
    foreach ($arr_res as $address_i) {
        if (mb_stripos($address_i['Address'], $_POST['address'])) {
            $res_search[] = $address_i;
        }
    }
    $countPage = floor(count($res_search) / $entriesPerPage);
    $subarr_res = array_slice($res_search, ($page - 1) * $entriesPerPage, $entriesPerPage);
}

//Поиск по фильтрам
if (
    (isset($_POST['HasEquipmentRental']) &&
        isset($_POST['HasDressingRoom']) &&
        isset($_POST['HasEatery']) &&
        isset($_POST['HasToilet']) &&
        isset($_POST['HasWifi']) &&
        isset($_POST['HasFirstAidPost']) &&
        isset($_POST['Lighting']) &&
        isset($_POST['DisabilityFriendly']) &&
        isset($_POST['Paid']) &&
        isset($_POST['Seats']) &&
        isset($_POST['SurfaceTypeWinter']) &&
        isset($_POST['HasTechService']))
    &&
    (
        $_POST['HasEquipmentRental'] !== 'Не выбрано' ||
        $_POST['HasDressingRoom'] !== 'Не выбрано' ||
        $_POST['HasEatery'] !== 'Не выбрано' ||
        $_POST['HasToilet'] !== 'Не выбрано' ||
        $_POST['HasWifi'] !== 'Не выбрано' ||
        $_POST['HasFirstAidPost'] !== 'Не выбрано' ||
        $_POST['Lighting'] !== 'Не выбрано' ||
        $_POST['DisabilityFriendly'] !== 'Не выбрано' ||
        $_POST['Paid'] !== 'Не выбрано' ||
        $_POST['Seats'] !== 'Не выбрано' ||
        $_POST['SurfaceTypeWinter'] !== 'Не выбрано' ||
        $_POST['HasTechService'] !== 'Не выбрано'
    )
) {
    $arr_for_filter = array();
    $arr_filter = array();
    if (isset($_POST['address']) && $_POST['address'] !== "") {
        $arr_for_filter = $res_search;
    } else {
        $arr_for_filter = $arr_res;
    }

    if ($_POST['HasEquipmentRental'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['HasEquipmentRental'] == $_POST['HasEquipmentRental']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['HasDressingRoom'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['HasDressingRoom'] == $_POST['HasDressingRoom']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }
    if ($_POST['HasEatery'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['HasEatery'] == $_POST['HasEatery']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['HasToilet'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['HasToilet'] == $_POST['HasToilet']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['HasWifi'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['HasWifi'] == $_POST['HasWifi']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['HasFirstAidPost'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['HasFirstAidPost'] == $_POST['HasFirstAidPost']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['Lighting'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['Lighting'] == $_POST['Lighting']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['DisabilityFriendly'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['DisabilityFriendly'] == $_POST['DisabilityFriendly']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['Paid'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['Paid'] == $_POST['Paid']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['Seats'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['Seats'] > 0 && $_POST['Seats'] == 'Да') {
                $filtered_arr[] = $row;
            } elseif ($row['Seats'] == 0 && $_POST['Seats'] == 'Нет') {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['SurfaceTypeWinter'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['SurfaceTypeWinter'] == $_POST['SurfaceTypeWinter']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }

    if ($_POST['HasTechService'] !== 'Не выбрано') {
        foreach ($arr_for_filter as $row) {
            if ($row['HasTechService'] == $_POST['HasTechService']) {
                $filtered_arr[] = $row;
            }
        }
        $arr_for_filter = $filtered_arr;
    }
    $countPage = floor(count($arr_for_filter) / $entriesPerPage);
    $subarr_res = array_slice($arr_for_filter, ($page - 1) * $entriesPerPage, $entriesPerPage);
}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style/styleIndex.css">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=c63929ca-1811-4d86-bdc5-1cfbeaa4a804&lang=ru_RU" type="text/javascript"></script>

    <title>Playground Finder Moscow</title>
</head>

<body>
    <header>
        <div class="container">
            <div class="row align-items-center">
                <div class="col-2 text-center">

                </div>
                <div class="col-8">
                    <p class="text-center fs-1 title">Playground Finder Moscow</p>
                </div>
                <div class="col-2 p-3">
                    <?php
                    if (isset($_SESSION['user'])) {
                        echo '<a href="php/profile.php"><button title = "Личный кабинет" class="btn btn-primary"><img src="img/profile.png"width="20px" height="20px ">';
                    } else {
                        echo '<a href="php/autoriz.php"><button title="Авторизируетесь что бы сохранять понравивщиеся вам площадки" class="btn btn-primary">Войти';
                    }
                    ?>
                    </button></a>
                </div>
            </div>
        </div>
    </header>
    <main>
        <div class="container about-site">
            <div class="row">
                <div class="col-md-4">
                    <img src="img/photoAbout.png" alt="Не удалось загрузить логотип" class="photo computer">
                </div>
                <div class="col-md-8 fs-5">
                    <p>"Welcome to Playground Finder Moscow - ваш путеводитель по спортивным площадкам в Москве!</p>
                    <p> Наш сайт предлагает широкий выбор спортивных площадок для всех возрастов и интересов. У нас вы
                        найдете идеальное место для
                        занятий спортом и активного образа жизни. </p>
                    <p>Так же вы можете зарегистрироваться на нашем сайте, что бы сохраять понравившиеся площадки.</p>
                    <p>Используйте нашу платформу, чтобы исследовать,
                        сравнить и найти лучшую спортивную площадку в вашем районе или по всей Москве. Начните прямо
                        сейчас и откройте весь потенциал вашей спортивной жизни!"</p>
                </div>
            </div>
        </div>

        <hr class="my-4 ">

        <div class="title-table">
            <p class="fs-4 text-center">Давайте подберем идеальную площадку для вас</p>
        </div>

        <div class="map container">
            <div id="yandexMap"></div>
        </div>
        <script>
            ymaps.ready(init);

            // Функция инициализации карты
            function init() {

                // Создание объекта карты
                var myMap = new ymaps.Map('yandexMap', {
                    center: [55.7558, 37.6176], // Координаты центра карты (центр Москвы)
                    zoom: 14 // Уровень масштабирования
                });

                <?php
                if (isset($_POST['address']) && $_POST['address'] !== "") {
                    $arr_coord = $res_search;
                } else {
                    $arr_coord = $arr_res;
                }

                foreach ($arr_coord as $row) :
                    $latitude = explode(",", $row['geoData'])[0];
                    $longitude = explode(",", $row['geoData'])[1];
                    $id = $row['global_id'];
                    $color = "#1E90FF";
                    if (isset($_SESSION['user'])) {
                        if (!empty($_SESSION['user']['favoriteGrounds']) && in_array($id, array_column($_SESSION['user']['favoriteGrounds'], 'global_id'))) {
                            $color =  '#ff0000';//избранные выделяются
                        }
                    }
                ?>
                    // Создание метки на карте с координатами площадки
                    var placemark = new ymaps.Placemark([<?= $longitude ?>, <?= $latitude ?>], {
                        hintContent: '<?= $row["ObjectName"] ?>'
                    }, {
                        iconColor: '<?= $color ?>'
                    });
                    placemark.events.add('click', function() {
                        window.location.href = 'php/card.php?id=' + <?= $id ?> + '&source=index';
                    });
                    myMap.geoObjects.add(placemark);
                <?php endforeach; ?>


                // Добавление поискового контрола
                var searchControlRight = new ymaps.control.SearchControl({
                    options: {
                        noPlacemark: true,
                        float: 'right',
                        provider: 'yandex#search'
                    }
                });

                // Добавление поискового контрола на карту
                myMap.controls.add(searchControlRight);

                // Обработка события изменения границ карты 
                myMap.events.add('boundschange', function(event) {
                    // Установка границ для поискового контрола, основанного на событии изменения границ
                    searchControlRight.options.set('boundedBy', event.get('newBounds'));
                });
            }
        </script>

        <div class="search container">
            <form action="?page=1" method="POST">
                <p class="text-center">
                    <input type="textarea" placeholder="Поиск по адресу" <?php if (isset($_POST['address']) && $_POST['address'] !== "") echo 'value =" ' . $_POST['address'] . '"'; ?> name="address" id="address-input" />
                    <button id="clear-button" class="btn btn-primary"><img src="img/close.png" width="20px" height="20px "></button>
                    <a href="?page=1"><button type="submit" class="btn btn-primary"><img src="img/search.png" width="20px" height="20px "></button></a>
                </p>
            </form>
        </div>

        <script>
            document.getElementById("clear-button").addEventListener("click", function() {
                document.getElementById("address-input").value = "";
            });
        </script>


        <form class="filters" action="?page=1" method="POST">
            <div class="container">
                <div class="row"><!--1-->
                    <div class="col-4">
                        <label for="HasEquipmentRental">Прокат оборудования:</label>
                        <select id="HasEquipmentRental" class="form-select" name="HasEquipmentRental">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['HasEquipmentRental'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['HasEquipmentRental']) && $_POST['HasEquipmentRental'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="HasDressingRoom">Раздевалка:</label>
                        <select id="HasDressingRoom" class="form-select" name="HasDressingRoom">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['HasDressingRoom'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['HasDressingRoom']) && $_POST['HasDressingRoom'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="HasEatery">Точки питания:</label>
                        <select id="HasEatery" class="form-select" name="HasEatery">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['HasEatery'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['HasEatery']) && $_POST['HasEatery'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row"><!--2-->
                    <div class="col-4">
                        <label for="HasToilet">Туалет:</label>
                        <select id="HasToilet" class="form-select" name="HasToilet">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['HasToilet'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['HasToilet']) && $_POST['HasToilet'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="HasWifi">Wi-Fi:</label>
                        <select id="HasWifi" class="form-select" name="HasWifi">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['HasWifi'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['HasWifi']) && $_POST['HasWifi'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="HasFirstAidPost">Пункт первой помощи:</label>
                        <select id="HasFirstAidPost" class="form-select" name="HasFirstAidPost">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['HasFirstAidPost'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['HasFirstAidPost']) && $_POST['HasFirstAidPost'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row"><!--3-->
                    <div class="col-4">
                        <label for="Lighting">Освещение:</label>
                        <select id="Lighting" class="form-select" name="Lighting">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['Lighting'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['Lighting']) && $_POST['Lighting'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="DisabilityFriendly">Оборудовано для инвалидов</label>
                        <select id="DisabilityFriendly" class="form-select" name="DisabilityFriendly">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['DisabilityFriendly'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['DisabilityFriendly']) && $_POST['DisabilityFriendly'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="Paid">Оплата</label>
                        <select id="Paid" class="form-select" name="Paid">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['Paid'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['Paid']) && $_POST['Paid'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>

                <div class="row"><!--4-->
                    <div class="col-4">
                        <label for="Seats">Оборудованые сидячие места</label>
                        <select id="Seats" class="form-select" name="Seats">
                            <option value="Не выбрано">Не выбрано</option>
                            <option value="Да" <?php if (isset($_POST['Seats']) && $_POST['Seats'] == 'Да') echo 'selected'; ?>>Да</option>
                            <option value="Нет" <?php if (isset($_POST['Seats']) && $_POST['Seats'] == 'Нет') echo 'selected'; ?>>Нет</option>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="SurfaceTypeWinter">
                            Покрытие в зимний период:</label>
                        <select id="SurfaceTypeWinter" class="form-select" name="SurfaceTypeWinter">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['SurfaceTypeWinter'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['SurfaceTypeWinter']) && $_POST['SurfaceTypeWinter'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-4">
                        <label for="HasTechService">Наличие сервиса технического обслуживания:</label>
                        <select id="HasTechService" class="form-select" name="HasTechService">
                            <option value="Не выбрано">Не выбрано</option>
                            <?php
                            foreach ($filterVariant['HasTechService'] as $row) {
                                echo '<option value="' . $row . '" ';
                                if (isset($_POST['HasTechService']) && $_POST['HasTechService'] == $row) echo 'selected';
                                echo '>' . $row . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class=" text-center my-3">
                <input type="submit" class="btn btn-primary " value="Применить">
            </div>
        </form>



        <div class="container posts">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Название</th>
                        <th>Адрес</th>
                        <?php
                        if (isset($_SESSION['user'])) {
                            echo '<th></th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = ($page - 1) * $entriesPerPage;
                    if (isset($_SESSION['message'])) {
                    ?>
                        <script>
                            swal("Успех", "<?= $_SESSION['message'] ?>", "success");
                        </script>
                    <?php
                        unset($_SESSION['message']);
                    }
                    foreach ($subarr_res as $row_t) :
                    ?>
                        <tr onclick="window.location='php/card.php?id=<?= $row_t['global_id'] ?>&source=index'">
                            <th><?= ++$i ?></th>
                            <th><?= $row_t['ObjectName'] ?></th>
                            <th><?= $row_t['Address'] ?></th>
                            <?php

                            if (isset($_SESSION['user'])) {
                                if (in_array($row_t['global_id'], array_column($_SESSION['user']['favoriteGrounds'], 'global_id'))) {
                                    echo '<th><a href="php/deleteFavorite.php?id=' . $row_t["global_id"] . '&source=index"><button class="btn btn-primary like-btn"><img src="img/fill-heart.png"width="20px" height="20px "></button></a>';
                                } else {
                                    echo '<th><a href="php/addfavorite.php?id=' . $row_t["global_id"] . '&source=index"><button class="btn btn-primary like-btn"><img src="img/empty-heart.png"width="20px" height="20px "></button></a>';
                                }
                            }
                            ?>

                        </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>

        <div class="container pagination">
            <?php
            if ($countPage > 1) :
            ?>
                <a href="?page=<?= 1 ?>"><button class="back-page btn btn-primary"><img src="img/back.png" width="20px" height="20px "></button></a>
                <?php
                if ($page < 5) {
                    for ($i = 1; $i < 9 + 1 && $i < $countPage; $i++) {
                ?>
                        <a href="?page=<?= $i ?>"><button class="btn btn-primary" <?php if ($i == $page) echo 'id = "current-page"' ?>><?= $i ?></button></a>
                    <?php
                    }
                } else {
                    for ($i = $page - 4; $i < $page + 5 && $i <= $countPage; $i++) {
                    ?>
                        <a href="?page=<?= $i ?>"><button class="btn btn-primary" <?php if ($i == $page) echo 'id = "current-page"' ?>><?= $i ?></button></a>
                <?php
                    }
                }
                ?>
                <a href="?page=<?= $countPage ?>"><button class="back-page btn btn-primary"><img src="img/next.png" width="20px" height="20px "></button></a>
            <?php
            endif;
            ?>
        </div>

    </main>
    <footer>
        <p class="text-center fs-6">Все права защищены</p>
        <p class="text-center fs-6">Данные взяты из <a href="https://data.mos.ru">портала открытых данных</a></p>
        <p class="text-center fs-6">Наши контакты</p>
        <p class="text-center fs-6"><a href="tel:+79537130547">+7 (953) 7130547</a></p>
        <p class="text-center fs-6"><a href="mailto:smnvsrg@yandex.ru">smnvsrg@yandex.ru</a></p>
    </footer>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">


</body>

</html>