<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');

$lot_get = (int) $_GET['id'];
$lot = getLotById ($con, $lot_get);
if (isset($lot) == false) {
    http_response_code(404);
    $error = http_response_code();
    $content = include_template('error_404.php', ['error' => $error]);
    print($content);
    die;
}

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'lots' => $lots,
    'lot' => $lot,
]);

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'site_name' => $site_name[0],
    'categories' => $categories ?? [],
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'is_auth' => $is_auth
]);
print($layout_content);
