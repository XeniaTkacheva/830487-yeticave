<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');
session_start();

unset($_SESSION['user']);
header("Location: /index.php");

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'site_name' => $site_name[0],
    'categories' => $categories ?? [],
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'is_auth' => $is_auth
]);
print($layout_content);
