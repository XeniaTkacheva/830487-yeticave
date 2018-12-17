<?php
date_default_timezone_set('Europe/Berlin');

$site_name = ['Главная страница', 'Регистрация аккаунта', 'Вход на сайт', 'Лоты по категории', 'Результаты поиска', 'Добавление нового лота', 'Просмотр лота', 'Список моих ставок'];
$user_name = null;
$user_avatar = 'img/icon-star.png';
$user = null;
$rate_add = [];

session_start();

if (isset($_SESSION['user'])){
$user = $_SESSION['user'];
$user_name = $_SESSION['user']['name'];
$user_avatar = $_SESSION['user']['avatar'] ?? 'img/icon-star.png';
};
?>
