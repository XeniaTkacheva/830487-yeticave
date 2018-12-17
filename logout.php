<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');

unset($_SESSION['user']);
header("Location: /index.php");
exit;
