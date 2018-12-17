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
    $time_format = sprintf('%02dд %02dч %02dм', $days_to_end, $hours_to_end, $minutes_to_end);
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

function getLotById ($con, $lot_get) {
    $sql = 'SELECT l.id, l.dt_add, l.name AS title, cat_id, c.name, picture, price_start, description, dt_end, rate_step, MAX(IF(rate_sum IS NULL, l.price_start, rate_sum)) AS price, l.user_id, u.email AS \'продавец\'
    FROM lots l
    JOIN categories c ON l.cat_id = c.id
    LEFT JOIN rates r ON l.id = r.lot_id
    JOIN users u ON u.id = l.user_id
    WHERE l.id = ' . mysqli_real_escape_string($con, $lot_get) . '
    GROUP BY l.id;';

    $result = checkQuery($con, $sql);
    $lot = mysqli_fetch_assoc($result);

    return $lot;
};

function getCatIdByName ($con, $cat_name) {
    $sql = 'SELECT id
    FROM categories 
    WHERE name = "' . mysqli_real_escape_string($con, $cat_name) . '";';

    //    WHERE name = "' . esc($cat_name) . '";';

    $result = checkQuery($con, $sql);
    $cat_id_arr = mysqli_fetch_assoc($result);
    if ($cat_id_arr === null) {
        http_response_code(404);
        $error = http_response_code();
        $content = include_template('error_404.php', ['error' => $error]);
        print($content);
        die;
    }
    $cat_id = (int) $cat_id_arr['id'];
    return $cat_id;
};
function getRateByLotId ($con, $lot_get) {
    $sql = 'SELECT  r.dt_add, rate_sum, user_id, lot_id, u.name FROM rates r
JOIN users u ON u.id = r.user_id
WHERE lot_id = ' . mysqli_real_escape_string($con, $lot_get) . '
ORDER BY dt_add DESC;';

    $result = checkQuery($con, $sql);
    $rates = mysqli_fetch_array($result);

    return $rates;
};

function db_get_prepare_stmt($link, $sql, $data = []) {
    $stmt = mysqli_prepare($link, $sql);
    if ($data) {
        $types = '';
        $stmt_data = [];
        foreach ($data as $value) {
            $type = null;
            if (is_int($value)) {
                $type = 'i';
            }
            else if (is_string($value)) {
                $type = 's';
            }
            else if (is_double($value)) {
                $type = 'd';
            }
            if ($type) {
                $types .= $type;
                $stmt_data[] = $value;
            }
        }
        $values = array_merge([$stmt, $types], $stmt_data);
        $func = 'mysqli_stmt_bind_param';
        $func(...$values);
    }

    return $stmt;
};

function addAvatarToUser ($con, $user_id, $file_url) {
    $sql = 'UPDATE users SET avatar = "' . $file_url . '" WHERE id = ' . $user_id . ';';
    $result = checkQuery($con, $sql);
    return $result;
};

function checkUniqueEmail ($con, $value) {
    $sql =  'SELECT id, email FROM users WHERE email = "' . mysqli_real_escape_string($con, $value) . '" LIMIT 1;';
    $result = checkQuery($con, $sql);
    $check = mysqli_fetch_assoc($result);
    if ($check !== null) {
        return false;
    }
    return true;
};

function getAllAboutUser ($con, $value)
{
    $sql = 'SELECT * FROM users WHERE email = "' . mysqli_real_escape_string($con, $value) . '";';
    $result = checkQuery($con, $sql);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

    return $user;
};

function getRatesByLotId ($con, $value) {
    $sql = 'SELECT  r.dt_add, rate_sum, user_id, lot_id, u.name FROM rates r
JOIN users u ON u.id = r.user_id
WHERE lot_id = ' . mysqli_real_escape_string($con, $value) . '
ORDER BY dt_add DESC;';

    $result = checkQuery($con, $sql);

    $rates = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $rates;
};

function renameImgGetUrl ($file_type) {
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
    return $file_url;
};
