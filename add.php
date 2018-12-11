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
    $for_sale['path'] = $file_name;

    $sql = 'INSERT INTO lots (dt_add, cat_id, user_id, name, description, path) 
            VALUES (NOW(), ?, 1, ?, ?, ?)';

    $stmt = db_get_prepare_stmt($con, $sql, [$for_sale['category'], $for_sale['title'], $for_sale['description'], $file_url]);
    var_dump($stmt);
    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        $lote_id = mysqli_insert_id($con);
        header("Location: lot.php?id=" . $lote_id);
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
