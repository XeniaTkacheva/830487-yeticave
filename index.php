<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');

$page_content = include_template('index.php', [
    'categories' => $categories,
    'lots' => $lots,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'site_name' => $site_name[0],
    'categories' => $categories ?? [],
    'user' => $user,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);
print($layout_content);
