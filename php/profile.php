<?php
session_start();
include 'dbConnector.php';

if (!$_SESSION['user']) {
    header('Location: autoriz.php');
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="shortcut icon" href="../img/favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/styleProfile.css">

    <script src="https://api-maps.yandex.ru/2.1/?apikey=c63929ca-1811-4d86-bdc5-1cfbeaa4a804&lang=ru_RU" type="text/javascript"></script>
    <title>Личный кабинет</title>
</head>

<body>
    <header>
        <div class="container overflow-hidden">
            <div class="row align-items-center">
                <div class="col-2"></div>
                <div class="col-8 text-center fs-1">Playground Finder Moscow</div>
                <div class="col-2">
                    <a href="../index.php"><button title = "На главную" class="btn btn-primary"><img src="../img/main.png" width="20px" height="20px "></button></a>
                    <a href="logout.php"><button title = "Выйти из аккаунта" class="btn btn-primary"><img src="../img/exit.png" width="20px" height="20px"></button></a>
                </div>
                <div class="row align-items-center">
                    <div class="col-12 text-center">
                        <p class="fs-3">Личный кабинет</p>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <main>
   
        <?php
        if (!empty($_SESSION['user']['favoriteGrounds'])) {
        ?>
         <p class="fs-4 text-center">Здесь находяться список избранных площадок</p>
            <div class="map container">
                <div id="yandexMap" class="text-center" style="width: 80%; height: 590px;"></div>
            </div>
            <script>
                ymaps.ready(init);

                // Функция инициализации карты
                function init() {
                    
                    // Создание объекта карты
                    var myMap = new ymaps.Map('yandexMap', {
                        center: [<?= explode(",", $_SESSION['user']['favoriteGrounds'][0]['geoData'])[1] ?>, <?= explode(",", $_SESSION['user']['favoriteGrounds'][0]['geoData'])[0] ?>], // Координаты центра карты (центр Москвы)
                        zoom: 14 // Уровень масштабирования
                    });

                    <?php
                    foreach ($_SESSION['user']['favoriteGrounds'] as $row) :
                        // Получение координат трассы
                        $latitude = explode(",", $row['geoData'])[0];
                        $longitude = explode(",", $row['geoData'])[1];
                        $id = $row['global_id'];
                    ?>
                        // Создание метки на карте с координатами трассы
                        var placemark = new ymaps.Placemark([<?= $longitude ?>, <?= $latitude ?>], {
                            hintContent: '<?= $row["ObjectName"] ?>'
                        }, {
                            iconColor: '#ff0000'
                        });
                        placemark.events.add('click', function() {
                            window.location.href = 'php/card.php?id=' + <?= $id ?> + '&source=profile';
                        });
                        myMap.geoObjects.add(placemark);
                    <?php endforeach; ?>


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
        <?php
        }
        ?>
<p class="text-center fs-3">Список избранного</p>
        <?php
        if (!empty($_SESSION['user']['favoriteGrounds'])) {
        ?>
            <div class="container posts">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Название</th>
                            <th>Адрес</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 0;
                        foreach ($_SESSION['user']['favoriteGrounds'] as $row) :
                        ?>
                            <tr onclick="window.location='card.php?id=<?= $row['global_id'] ?>&source=profile'">
                                <th><?= ++$i ?></th>
                                <th><?= $row['ObjectName'] ?></th>
                                <th><?= $row['Address'] ?></th>
                                <?php
                                echo '<th><a href="deleteFavorite.php?id=' . $row["global_id"] . '&source=profile"><button class="btn btn-primary like-btn"><img src="../img/fill-heart.png"width="20px" height="20px "></button></a>';
                                ?>
                            </tr>
                        <?php
                        endforeach;
                        ?>
                    </tbody>
                </table>
            </div>
        <?php
        } else {
            echo "<p class='text-center'>У вас нет избранных площадок</p>";
            echo "<p class='text-center'>Добавте площадки в избранное и они всегда будут у вас под рукой</p>";
        }
        ?>
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