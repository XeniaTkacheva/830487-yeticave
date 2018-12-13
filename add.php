<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $for_sale = $_POST['for_sale'];

    $required = ['title', 'category', 'description', 'price_start', 'step', 'dt_end'];
    $dict = ['title' => 'Наименование', 'category' => 'Категория', 'description' => 'Описание', 'picture' => 'Изображение', 'price_start' => 'Начальная цена', 'step' => 'Шаг ставки', 'dt_end' => "Дата окончания торгов"];
    $errors = [];
    foreach ($required as $key) {
        if (empty($for_sale[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    };

    if (isset($_FILES['jpg_img']['name']) && !empty ($_FILES['jpg_img']['name'])) {
        $file_name = $_FILES['jpg_img']['tmp_name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $file_name);
        if ($file_type !== "image/png" & $file_type !== "image/jpeg" & $file_type !== "image/jpg") {
            $errors['picture'] = 'Загрузите картинку в формате JPG, JPEG или PNG';
        } else {
            if ($file_type === "image/png") {
                $file_name = uniqid() . '.png';
            } elseif ($file_type === "image/jpg") {
                $file_name = uniqid() . '.jpg';
            } elseif ($file_type === "image/jpeg") {
                $file_name = uniqid() . '.jpeg';
            }
            $file_path = __DIR__ . '/img/';
            $file_url = '/img/' . $file_name;
            move_uploaded_file($_FILES['jpg_img']['tmp_name'], $file_path . $file_name);
//    print("<a href='$file_url'>$file_name</a>");
        }
    } else {
        $errors['picture'] = 'Вы не загрузили файл';
    };

    if (empty($for_sale['price_start']) || $for_sale['price_start'] <= 0)  {
        $errors['price_start'] = 'Введите цену больше нуля';
    }

    if (empty($for_sale['description']))  {
        $errors['description'] = 'Напишите описание лота, это очень важно';
    }

    if (empty($for_sale['step']) || $for_sale['step'] <= 0 || $for_sale['step'] !== (string)(int)$for_sale['step'])  {
        $errors['step'] = 'Введите шаг ставки в виде положительного целого числа';
    }
    $for_sale['dt_end'] = date("d.m.Y" , strtotime($for_sale['dt_end']));
    if (empty($for_sale['dt_end']) || $for_sale['dt_end'] < (date('d.m.Y',strtotime('today + 1 day')))) {
        $errors['dt_end'] = 'Введите дату завершения торгов позднее завтрашнего дня';
    };
    if (count($errors)) {
        $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';
        $page_content = include_template('add_lot.php', [
            'for_sale' => $for_sale,
            'errors' => $errors,
            'dict' => $dict,
            'categories' => $categories,
            'lots' => $lots,
        ]);
    } else {
        $for_sale['picture'] = $file_url;

        $cat_name = $for_sale['category'];
        $cat_id = getCatIdByName ($con, $cat_name);

        $sql = 'INSERT INTO lots (dt_add, cat_id, user_id, name, description, picture, price_start, rate_step, dt_end) 
                VALUES (NOW(), ?, 1, ?, ?, ?, ?, ?, ?)';
        $stmt = db_get_prepare_stmt($con, $sql, [$cat_id, $for_sale['title'], $for_sale['description'],
            $for_sale['picture'], $for_sale['price_start'], $for_sale['step'], $for_sale['dt_end']]);
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
    $page_content = include_template('add_lot.php', [
        'categories' => $categories,
        'lots' => $lots,
    ]);
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'site_name' => $site_name[0],
    'categories' => $categories ?? [],
    'user_name' => $user_name,
    'user_avatar' => $user_avatar,
    'is_auth' => $is_auth
]);
print($layout_content);
