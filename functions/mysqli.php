<?php
require_once('../config.php');

$mysqli = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
if($mysqli->connect_error)
	die('0|DB 접속 오류 ('.$mysqli->connect_errno.') '.$mysqli->connect_error);
$mysqli->query("SET SESSION CHARACTER_SET_CONNECTION = UTF8");
$mysqli->query("SET SESSION CHARACTER_SET_RESULTS = UTF8");
$mysqli->query("SET SESSION CHARACTER_SET_CLIENT = UTF8");
?>