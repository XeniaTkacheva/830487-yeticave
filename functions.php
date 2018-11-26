<?php
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

function format_price($price) {
    $price_int = ceil($price);
    if ($price_int >= 1000) {
        $pricef = number_format($price_int, 0, ',', ' ');
        } else {
        $pricef = $price_int;
        }
    return ($pricef . ' &#x20BD;');
};

function esc($str) {
    $text = htmlspecialchars($str);

    return $text;
};

function to_midnight() {
    $cur_time = strtotime('now');
    $midnight = strtotime('tomorrow midnight');
    $sec_to_midnight = $midnight - $cur_time;
    $hours_to_midnight = floor(($sec_to_midnight) / 3600);
    $minutes_to_midnight = floor(($sec_to_midnight - $hours_to_midnight * 3600) / 60);
    return $hours_to_midnight . ' : ' . $minutes_to_midnight;
};
