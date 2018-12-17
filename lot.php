<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');

if (!isset($user)) {
    $rate_add['not_user'] = 'Вы не авторизованы';
};

$lot_get = (int)$_GET['id'];
$lot = getLotById($con, $lot_get);
if (isset($lot) == false) {
    http_response_code(404);
    $error = http_response_code();
    $content = include_template('error_404.php', ['error' => $error]);
    print($content);
    die;
}

$rates = getRatesByLotId ($con, $lot['id']);
if (strtotime($lot['dt_end']) < strtotime('now')) {
    $rate_add['dt_end'] = 'Лот закрыт';
};
if ($lot['user_id'] === $user['id']) {
    $rate_add['user_id'] = 'Вы не можете ставить на свой лот';
};
foreach ($rates as $value) {
    if ($value['user_id'] === $user['id']) {
        $rate_add['double'] = 'Вы не можете ставить повторно';
    }
};

$page_content = include_template('lot.php', [
    'categories' => $categories,
    'lots' => $lots,
    'lot' => $lot,
    'rates' => $rates,
    'rate_add' =>$rate_add,
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
