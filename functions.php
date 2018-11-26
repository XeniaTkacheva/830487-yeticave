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
