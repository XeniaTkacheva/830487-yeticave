<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');

$cat_id = (int) $_GET['cat_id'];
$lots = getLotsByCatId ($con, $cat_id);
$cat_name = $lots[0]['category'] ?? "";
$page_content = include_template('all_lots.php', [
    'cat_name' => $cat_name,
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
