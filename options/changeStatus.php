<?php
define('NOAUTH',true);
require_once "../base.php";
session_start();

$value = $_POST['value'];
$status = $_POST['status'];
$_SESSION[$status] = $value;

echo json_encode("");

?>