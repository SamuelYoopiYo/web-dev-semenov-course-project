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
    <link rel="shortcut icon" href="img/favicon.ico">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../style/styleProfile.css">
    <title>Личный кабинет</title>
</head>

<body>
    <header>
        <div class="container overflow-hidden">
            <div class="row align-items-center">
                <div class="col-2"></div>
                <div class="col-8 text-center fs-1">Личный кабинет</div>
                <div class="col-1"><a href="../index.php"><button class="btn btn-primary">Главная</button></a></div>
                <div class="col-1"><a href="logout.php"><button class="btn btn-primary">Выход</button></a></div>
                <div class="row align-items-center">
                    <div class="col-12 text-center">
                        <p class="fs-3"><?= $_SESSION['user']['name'] ?></p>
                    </div>
                </div>
            </div>
        </div>
    </header>


    <main>
        <?php 
        print_r($_SESSION['user']['favoriteGrounds']['global_id']);
       if(!empty($_SESSION['user']['favoriteGrounds'])){
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
                            echo '<th><a href="deleteFavorite.php?id='. $row["global_id"] .'&source=profile"><button class="btn btn-primary like-btn">&#128465;</button></a>';
                            ?>
                        </tr>
                    <?php
                    endforeach;
                    ?>
                </tbody>
            </table>
        </div>
        <?php 
        }else{
        echo "<p class='text-center'>У вас нет избраных площадок</p>";
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