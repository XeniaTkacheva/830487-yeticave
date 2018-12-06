<?php
// Функция подключения шаблона

function include_template($name, $data) {
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
        }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
};

// Функция форматирования цены лота

function format_price($price) {
    $price_int = ceil($price);
    if ($price_int >= 1000) {
        $pricef = number_format($price_int, 0, ',', ' ');
        } else {
        $pricef = $price_int;
        }
    return ($pricef . ' &#x20BD;');
};

// Функция для безопасности при внешних данных
function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
};

// Функция расчета времени до полуночи

function to_midnight() {
    $cur_time = strtotime('now');
    $midnight = strtotime('tomorrow midnight');
    $sec_to_midnight = $midnight - $cur_time;
    $hours_to_midnight = floor(($sec_to_midnight) / 3600);
    $minutes_to_midnight = floor(($sec_to_midnight - $hours_to_midnight * 3600) / 60);
    $time_format = sprintf('%02d:%02d', $hours_to_midnight, $minutes_to_midnight);
    return $time_format;
};

// Функция расчета времени до окончания лота

function check_time_end($dt_end) {
    $cur_time = strtotime('now');
    $end_time = strtotime($dt_end);
    $sec_to_end = $end_time - $cur_time;
    $days_to_end = floor(($sec_to_end) / 86400);
    $hours_to_end = floor(($sec_to_end - $days_to_end * 86400) / 3600);
    $minutes_to_end = floor(($sec_to_end - $days_to_end * 86400 - $hours_to_end * 3600) / 60);
    $time_format = sprintf('%02d д %02d ч %02d м', $days_to_end, $hours_to_end, $minutes_to_end);
    return $time_format;
};

// Функция проверки запроса

function checkQuery($con, $sql) {
    $result = mysqli_query($con, $sql);
    if (!$result) {
        $error = mysqli_error($con);
        $page_content = include_template('error.php', ['error' => $error]);
        print($page_content);
        die;
    }
    return $result;
};
