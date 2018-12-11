<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $for_sale = $_POST['for_sale'];

    $file_name = uniqid() . '.jpg';
    $file_path = __DIR__ . '/img/';
    $file_url = '/img/' . $file_name;
    move_uploaded_file($_FILES['jpg_img']['tmp_name'], $file_path . $file_name);
//    print("<a href='$file_url'>$file_name</a>");
    $cat_name = $for_sale['category'];
    $cat_id = getCatIdByName ($con, $cat_name);

    $sql = 'INSERT INTO lots (dt_add, cat_id, user_id, name, description, picture, price_start, rate_step, dt_end) 
            VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';
    $stmt = db_get_prepare_stmt($con, $sql, [$cat_id, $for_sale['title'], $for_sale['description'],
        $file_url, $for_sale['rate'], $for_sale['step'], $for_sale['dt_end']]);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        $lote_id = mysqli_insert_id($con);
        header("Location: lot.php?id=" . $lote_id);
    }
    else {
        $content = include_template('error.php', ['error' => mysqli_error($con)]);
        print $content;
        die;
    }
}

$page_content = include_template('add_lot.php', [
    'categories' => $categories,
    'lots' => $lots,
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
