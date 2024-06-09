<?php
    session_start();
    $id = $_GET['id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="../img/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../style/styleCard.css">

    <script src="https://api-maps.yandex.ru/2.1/?apikey=5bf9531b-4adf-4788-8f43-5e042df3061f&lang=ru_RU"
        type="text/javascript"></script>
    <script src="../js/card.js"></script>
    <title>Описание</title>
</head>

<body>
    <header>
        <div class="container ">
            <div class="row align-items-center">
                <div class="col-2"></div>
                <div class="col-8">
                    <p class="fs-2 text-center "> Описание площадки</p></div>
                <div class="col-2">
                    <?php
                    if ($_SESSION['user']) {
                        if (in_array( $id , array_column($_SESSION['user']['favoriteGrounds'], 'global_id'))) { 
                            echo '<th><a href="deleteFavorite.php?id=' .  $id  . '&source=card&presourse='.$_GET['source'].'"><button class="btn btn-primary like-btn">&#128465;</button></a>';
                        } else {
                            echo '<th><a href="addFavorite.php?id=' .  $id  . '&source=card&presourse='.$_GET['source'].'"><button class="btn btn-primary like-btn">&hearts;</button></a>';
                        }
                    }
                    ?>
                
                    <a href="
                    <?php 
                    if($_GET['source'] == 'index'){
                        echo'../index.php';
                    }
                    elseif($_GET['source'] == 'profile'){
                        echo 'profile.php';
                    }
                    ?>
                    "><button class="btn btn-primary">Назад</button><a>
                </div>
            </div>
            
        </div>
    </header>
    <main>
        <div class="map container">
            <div class="row justify-content-center">
                <div id="map"></div>
            </div>
        </div>
        <div class="container">
            <div class="mainInfo"></div>
        </div>
    </main>
    <footer>
        <p class="text-center fs-6">Все права защищены</p>
        <p class="text-center fs-6">Данные взяты из <a href="https://data.mos.ru">портала открытых данных</a></p>
        <p class="text-center fs-6">Наши контакты</p>
        <p class="text-center fs-6"><a href="tel:+79537130547">+7 (953) 7130547</a></p>
        <p class="text-center fs-6"><a href="mailto:smnvsrg@yandex.ru">smnvsrg@yandex.ru</a></p>
    </footer>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</body>

</html>