<?php
require_once ('functions.php');
require_once('data.php');
require_once('queries.php');
session_start();

unset($_SESSION['user']);
header("Location: /index.php");
exit;
