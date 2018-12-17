<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');
session_start();


if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user']['name'];
    $user_avatar = $_SESSION['user']['avatar'];
    $user = $_SESSION['user'];
    $user_id = $_SESSION['user']['id'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_rate = $_POST['new_rate'];

        $lot_get = (int) $new_rate['lot_id'];
        $lot = getLotById($con, $lot_get);
        if (isset($lot) == false) {
            http_response_code(404);
            $error = http_response_code();
            $content = include_template('error_404.php', ['error' => $error]);
            print($content);
            die;
        }

        $required = ['cost'];
        $dict = ['cost' => 'Ставка'];
        $errors = [];

        if (empty($new_rate['cost']) || $new_rate['cost'] <= 0 || $new_rate['cost'] !== (string)(int)$new_rate['cost'])  {
            $errors['cost'] = 'Введите ставку в виде положительного целого числа';
        }

        if (!empty($new_rate['cost'])) {
            if ($new_rate['cost'] < ($lot['price'] + $lot['rate_step'])) {

                $errors['cost'] = 'Введите вашу ставку равную или больше минимальной';
            }
        }
        $rates = getRatesByLotId ($con, $lot['id']);

        if (count($errors)) {
//            $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';
            $page_content = include_template('lot.php', [
                'new_rate' => $new_rate,
                'errors' => $errors,
                'dict' => $dict,
                'categories' => $categories,
                'lots' => $lots,
                'lot' => $lot,
                'rates' => $rates,
                'rate_add' => $rate_add
                ]);
        } else {
            $sql = 'INSERT INTO rates SET rate_sum = ' . mysqli_real_escape_string($con, ((int)$new_rate['cost'])) . ', user_id = ' . $user_id . ', lot_id = ' . $lot['id'] . ';';
            $result = checkQuery($con, $sql);
            if ($result) {
                header("Location: lot.php?id=" . $lot['id']);
                exit;
            } else {
                $content = include_template('error.php', ['error' => mysqli_error($con)]);
                print $content;
                die;
            }
        }

    } else {
        $page_content = include_template('lot.php', [
            'categories' => $categories,
            'lots' => $lots,
        ]);
    }

} else {
    header("Location: index.php");
    exit;
};

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'site_name' => $site_name[0],
    'categories' => $categories ?? [],
    'user' => $user,
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
]);
print($layout_content);
