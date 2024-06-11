<?php
session_start();
$id = $_GET['id'];
include 'dbConnector.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$nameColumnQuery = mysqli_query($mysql, "SELECT * FROM `name_columns`;");
$nameColumn = array();
while ($row = mysqli_fetch_assoc($nameColumnQuery)) {
    $nameColumn[] = $row;
}

$infoGroundByIdQuery = mysqli_query($mysql, "SELECT *  FROM `info_playground` WHERE `global_id` =" . $id . " ;");
$infoGroundById = array();
while ($row = mysqli_fetch_assoc($infoGroundByIdQuery)) {
    $infoGroundById[] = $row;
}


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style/styleCard.css">

    <script src="https://api-maps.yandex.ru/2.1/?apikey=c63929ca-1811-4d86-bdc5-1cfbeaa4a804&lang=ru_RU" type="text/javascript"></script>

    <title>Описание</title>
</head>

<body>
    <header>
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-2"></div>
                <div class="col-8">
                    <p class="fs-2 text-center "> Playground Finder Moscow</p>
                </div>
                <div class="col-2">
                    <?php
                    if (isset($_SESSION['user'])) {
                        if (in_array($id, array_column($_SESSION['user']['favoriteGrounds'], 'global_id'))) {
                            echo '<th><a href="deleteFavorite.php?id=' .  $id  . '&source=card&presourse=' . $_GET['source'] . '"><button class="btn btn-primary like-btn"><img src="../img/fill-heart.png"width="20px" height="20px "></button></a>';
                        } else {
                            echo '<th><a href="addFavorite.php?id=' .  $id  . '&source=card&presourse=' . $_GET['source'] . '"><button class="btn btn-primary like-btn"><img src="../img/empty-heart.png"width="20px" height="20px "></button></a>';
                        }
                    }
                    ?>

                    <a href="
                    <?php
                    if ($_GET['source'] == 'index') {
                        echo '../index.php';
                    } elseif ($_GET['source'] == 'profile') {
                        echo 'profile.php';
                    }
                    ?>
                    "><button class="btn btn-primary"><img src="../img/back.png"width="20px" height="20px "></button><a>

                </div>
            </div>
            <div class="row align-items-center">
                    <div class="col-12 text-center">
                        <p class="fs-3">Описание площадки</p>
                    </div>
                </div>
        </div>
    </header>

    <main>
        <div class="map container text-center">
            <div id="yandexMap" class = "row">
                <script>
                    ymaps.ready(init);

                    // Функция инициализации карты
                    function init() {
                        <?php
                        $latitude = explode(",", $infoGroundById[0]['geoData'])[0];
                        $longitude = explode(",", $infoGroundById[0]['geoData'])[1];
                        ?>
                        // Создание объекта карты
                        var myMap = new ymaps.Map('yandexMap', {
                            center: [<?= $longitude ?>, <?= $latitude ?>], // Координаты центра карты (центр Москвы)
                            zoom: 14 // Уровень масштабирования
                        });


                        // Создание метки на карте с координатами площадки
                        var placemark = new ymaps.Placemark([<?= $longitude ?>, <?= $latitude ?>], {
                            hintContent: '<?= $infoGroundById[0]["ObjectName"] ?>',
                            balloonContent: 'Название объекта: <?= $infoGroundById[0]["ObjectName"] ?><br>Адрес: <?= $infoGroundById[0]["AdmArea"] . " " . $infoGroundById[0]["District"] . " " . $infoGroundById[0]["Address"] ?>'
                        }, {
                            iconColor: '#ff0000'
                        });
                        myMap.geoObjects.add(placemark);


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
            </div>
        </div>


        <div class="mainInfo container">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Характеристика</th>
                        <th>Наличие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($nameColumn as $row) {

                    ?>
                        <tr>
                            <th><?= $row['ru_name'] ?></th>
                            <th><?php
                                if (!empty($infoGroundById[0][$row['en_name']])) {
                                    echo $infoGroundById[0][$row['en_name']];
                                } else {
                                    echo '-';
                                }
                                ?></th>
                        </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
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