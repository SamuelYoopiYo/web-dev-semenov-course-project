<?php
session_start();
include 'php/dbConnector.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$result = mysqli_query($mysql, "SELECT `global_id`, `ObjectName`, CONCAT(`District`,' ', `Address`) as `Address`, `geoData` FROM `info_playground`;");
if (!$result) {
    echo '<p>Не удалось получить данные с БД </p>';
}

$arr_res = array();
while ($row = mysqli_fetch_assoc($result)) {
    $arr_res[] = $row;
}
if (isset($_GET['page'])) {
    $page = $_GET['page'];
} else {
    $page = 1;
}

$entriesPerPage = 50;
$countPage = ceil(count($arr_res) / $entriesPerPage);
$subarr_res = array_slice($arr_res, ($page - 1) * $entriesPerPage, $entriesPerPage);

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
                        echo '<a href="php/profile.php"><button class="btn btn-primary">Личный кабинет';
                    } else {
                        echo '<a href="php/regist.php"><button class="btn btn-primary">Войти';
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
                    <p>Используйте нашу удобную платформу, чтобы исследовать,
                        сравнить и найти лучшую спортивную площадку в вашем районе или по всей Москве. Начните прямо
                        сейчас и откройте весь потенциал вашей спортивной жизни!"</p>
                </div>
            </div>
        </div>

        <hr class="my-4 ">

        <div class="title-table">
            <p class="fs-4 text-center">Давайте подберем идеальную площадку для вас</p>
        </div>

        <div class="map">
            <div id="yandexMap" style="width: 100%; height: 590px;"></div>
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
                foreach ($arr_res as $row) :
                    // Получение координат трассы
                    $latitude = explode(",", $row['geoData'])[0];
                    $longitude = explode(",", $row['geoData'])[1];
                    $id = $row['global_id'];
                    $color = "#1E90FF";
                    if (isset($_SESSION['user'])) {
                        if (!empty($_SESSION['user']['favoriteGrounds']) && in_array($id, array_column($_SESSION['user']['favoriteGrounds'], 'global_id'))) {
                            $color =  '#ff0000'; // Указан неправильный цвет, должно быть '#ff0000', а не '#ff000'
                        }
                    }
                ?>
                    // Создание метки на карте с координатами трассы
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
                    <button id="clear-button" class="btn btn-primary">x</button>
                    <a href="?page=1"><button type="submit" class="btn btn-primary">&#128269;</button></a>
                </p>
            </form>
        </div>

        <script>
            document.getElementById("clear-button").addEventListener("click", function() {
                document.getElementById("address-input").value = "";
            });
        </script>

        <?php
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
        ?>


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
                                    echo '<th><a href="php/deleteFavorite.php?id=' . $row_t["global_id"] . '&source=index"><button class="btn btn-primary like-btn">&#128465;</button></a>';
                                } else {
                                    echo '<th><a href="php/addfavorite.php?id=' . $row_t["global_id"] . '&source=index"><button class="btn btn-primary like-btn">&hearts;</button></a>';
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
                <a href="?page=<?= 1 ?>"><button class="back-page btn btn-primary"><-< /button></a>
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
                <a href="?page=<?= $countPage ?>"><button class="back-page btn btn-primary">-></button></a>
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