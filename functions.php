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

function getRateByLotId ($con, $lot_get) {
    $sql = 'SELECT  r.dt_add, rate_sum, user_id, lot_id, u.name FROM rates r
JOIN users u ON u.id = r.user_id
WHERE lot_id = ' . mysqli_real_escape_string($con, $lot_get) . '
ORDER BY dt_add DESC;';

    $result = checkQuery($con, $sql);
    $rates = mysqli_fetch_array($result);

    return $rates;
};

/**
 * Создает подготовленное выражение на основе готового SQL запроса и переданных данных
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string SQL запрос с плейсхолдерами вместо значений
 * @param array $data Данные для вставки на место плейсхолдеров
 *
 * @return mysqli_stmt Подготовленное выражение
 */

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

/**
 * Получает массив действующих лотов на основе готового SQL запроса и GET запроса из формы поиска
 *
 * @param mysqli $con  Ресурс соединения
 * @param string $search Запрос _GET из строки писка
 *
 * @return array $lots  массив действующих лотов
 */

function searchLots ($con, $search) {
    $sql = 'SELECT DISTINCT l.id, l.name AS title, price_start, picture AS image_url, c.name AS category, dt_end, MAX(IF(rate_sum IS NULL, price_start, rate_sum)) AS price, COUNT(lot_id) AS rates_number
  FROM lots l
  JOIN categories c ON l.cat_id = c.id
  LEFT JOIN rates r ON l.id = r.lot_id
  WHERE dt_end > CURRENT_TIMESTAMP and winner_id IS NULL
  and MATCH (l.name, description) AGAINST("' . mysqli_real_escape_string($con, $search) . '"  IN BOOLEAN MODE)
  GROUP BY l.id, l.name, price_start, picture, c.name
  ORDER BY l.id DESC;';
    $result = checkQuery($con, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
};


/**
 * Получает массив действующих лотов на основе готового SQL запроса и GET строки запроса из меню категории
 *
 * @param mysqli $con  Ресурс соединения
 * @param integer $cat_id Запрос _GET из меню категорий
 *
 * @return array $lots  массив действующих лотов
 */

function getLotsByCatId ($con, $cat_id) {
    $sql = 'SELECT DISTINCT l.id, l.name AS title, price_start, picture AS image_url, c.name AS category, dt_end, 
MAX(IF(rate_sum IS NULL, price_start, rate_sum)) AS price, COUNT(lot_id) AS rates_number
  FROM lots l
  JOIN categories c ON l.cat_id = c.id
  LEFT JOIN rates r ON l.id = r.lot_id
  WHERE dt_end > CURRENT_TIMESTAMP and winner_id IS NULL
  and l.cat_id = ' . mysqli_real_escape_string($con, $cat_id) . '
  GROUP BY l.id, l.name, price_start, picture, c.name
  ORDER BY l.id DESC;';
    $result = checkQuery($con, $sql);
    $lots = mysqli_fetch_all($result, MYSQLI_ASSOC);

    return $lots;
};

