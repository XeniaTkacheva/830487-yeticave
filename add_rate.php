<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');
session_start();
if (isset($_SESSION['user'])) {
    $user_name = $_SESSION['user']['name'];
    $user_avatar = $_SESSION['user']['avatar'];

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $new_rate = $_POST['new_rate'];
//        var_dump($_GET, $_POST); die;

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

        if (empty($new_rate['cost']) || $new_rate['cost'] <= 0) {
            $errors['cost'] = 'Введите ставку больше нуля';
        }

        if (empty($new_rate['cost']) || $new_rate['cost'] <= 0 || $new_rate['cost'] !== (string)(int)$new_rate['cost'])  {
            $errors['cost'] = 'Введите ставку в виде положительного целого числа';
        }

        if (!empty($new_rate['cost'])) {



            if ($new_rate['cost'] < ($lot['price'] + $lot['rate_step'])) {

                $errors['cost'] = 'Введите ставку больше нуля';
            }
        }

        //    $new_rate['dt_end'] = date("d.m.Y" , strtotime($new_rate['dt_end']));

        if (count($errors)) {
//            $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';
            $page_content = include_template('lot.php', [
                'new_rate' => $new_rate,
                'errors' => $errors,
                'dict' => $dict,
                'categories' => $categories,
                'lots' => $lots,
                'lot' => $lot,


            ]);
        } else {



            $cat_name = $new_rate['category'];
            $cat_id = getCatIdByName ($con, $cat_name);

            $sql = 'INSERT INTO lots (dt_add, cat_id, user_id, name, description, picture, price_start, rate_step, dt_end) 
                    VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';
            $stmt = db_get_prepare_stmt($con, $sql, [$cat_id, $new_rate['title'], $new_rate['description'],
                $new_rate['picture'], $new_rate['price_start'], $new_rate['step'], $new_rate['dt_end']]);
            $res = mysqli_stmt_execute($stmt);

            if ($res) {
                $lot = mysqli_insert_id($con);
                header("Location: lot.php?id=" . $lot);
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
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'is_auth' => $is_auth
]);
print($layout_content);
