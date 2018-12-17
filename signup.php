<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_user = $_POST['new_user'];

    $required = ['email', 'password', 'user_name', 'contacts'];
    $dict = ['email' => 'E-mail*', 'password' => 'Пароль', 'user_name' => 'Имя пользователя', 'contacts' => 'Контактные данные', 'avatar' => 'Аватар'];
    $errors = [];
    foreach ($required as $key) {
        if (empty($new_user[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    };
    if (isset($_FILES['jpg_img']['name']) && !empty ($_FILES['jpg_img']['name'])) {
        $file_name = $_FILES['jpg_img']['tmp_name'];

        $file_type = mime_content_type($file_name);
        if ($file_type !== "image/png" & $file_type !== "image/jpeg" & $file_type !== "image/jpg") {
            $errors['avatar'] = 'Загрузите картинку в формате JPG, JPEG или PNG';
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
        }
    }
    if (!empty($new_user['email'])) {
        if (!filter_var($new_user['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Введите действующий E-mail';
        } elseif (!checkUniqueEmail($con, $new_user['email'])) {
            $errors['email'] = 'Пользователь с таким E-mail уже существует. Введите другой E-mail';
        }
    }
    if (!empty($new_user['password'])) {
        $passwordHash = password_hash($new_user['password'], PASSWORD_DEFAULT);
    } else {
        $errors['password'] = 'Введите пароль длиной не менее 8 знаков';
    }

    if (count($errors)) {
        $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';

        $page_content = include_template('sign_up.php', [
            'new_user' => $new_user,
            'errors' => $errors,
            'dict' => $dict,
            'categories' => $categories,
            'lots' => $lots,
        ]);
    }

    else {
        $new_user['password'] = $passwordHash;
        $sql = 'INSERT INTO users (dt_add, email, name, password, contacts)
                VALUES (NOW(), ?, ?, ?, ?);';
        $stmt = db_get_prepare_stmt($con, $sql, [$new_user['email'], $new_user['user_name'], $new_user['password'], $new_user['contacts']]);
        $res = mysqli_stmt_execute($stmt);

        if ($res) {
            $user_id = mysqli_insert_id($con);
            if (isset($file_url)) {
                addAvatarToUser ($con, $user_id, $file_url);
            }

            header("Location: login.php");
            exit;
        } else {
            $content = include_template('error.php', ['error' => mysqli_error($con)]);
            print $content;
            die;
        }
    }
} else {
    $page_content = include_template('sign_up.php', [
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
]);
print($layout_content);
