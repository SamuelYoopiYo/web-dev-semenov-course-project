<?php
define('DB_HOST', 'std-mysql.ist.mospolytech.ru'); // Адрес
define('DB_USER', 'std_2471_kursach');      // Имя пользователя
define('DB_PASSWORD', '12345678');      // Пароль
define('DB_NAME', 'std_2471_kursach'); // Имя БД

// Подключение к базе данных
$mysql = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

// Проверка соединения
if ($mysql->connect_error) {
    die('Ошибка подключения: ' . $mysql->connect_error);
} 
?>
