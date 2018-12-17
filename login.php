<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old_user = $_POST['old_user'];

    $required = ['email', 'password'];
    $dict = ['email' => 'E-mail*', 'password' => 'Пароль'];
    $errors = [];
    foreach ($required as $key) {
        if (empty($old_user[$key])) {
            $errors[$key] = 'Это поле надо заполнить';
        }
    };

    if (!empty($old_user['email'])) {
        if (filter_var($old_user['email'], FILTER_VALIDATE_EMAIL)) {
            $user = getAllAboutUser($con, $old_user['email']);
            if (!$user) {
                $errors['email'] = 'Пользователь с таким E-mail не найден';
            }
            if (!count($errors) and $user) {
                if (password_verify($old_user['password'], $user['password'])) {
                    $_SESSION['user'] = $user;
                } else {
                    $errors['password'] = 'Вы ввели неверный пароль';
                }
            }
        } else {
            $errors['email'] = 'Введите действующий E-mail в правильном формате';
        }
    }

    if (count($errors)) {
        $errors['form'] = 'Пожалуйста, исправьте ошибки в форме.';

        $page_content = include_template('log_in.php', [
            'old_user' => $old_user,
            'errors' => $errors,
            'dict' => $dict,
            'categories' => $categories,
            'lots' => $lots,
        ]);
    } else {
        $user_name = $_SESSION['user']['name'];
        $user_avatar = $_SESSION['user']['avatar'];
        header("Location: index.php");
        exit;
    }

} else {
    if (isset($_SESSION['user'])) {
        $user_name = $_SESSION['user']['name'];
        $user_avatar = $_SESSION['user']['avatar'];
        $user = $_SESSION['user'];

        $page_content = include_template('index.php', [
            'categories' => $categories,
            'lots' => $lots,
            'user_name' => $user_name]);
    }
    else {
        $page_content = include_template('log_in.php', [
            'categories' => $categories,
            'lots' => $lots,
        ]);
    }
}

$layout_content = include_template('layout.php', [
    'content' => $page_content,
    'site_name' => $site_name[0],
    'categories' => $categories ?? [],
    'user_name' => $user_name,
    'user' => $user,
    'user_avatar' => $user_avatar,
]);
print($layout_content);
