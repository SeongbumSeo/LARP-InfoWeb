<?php
session_start();

require_once('../config.php');
require_once('mysqli.php');

if (!isset($_SESSION['id'])) {
	print('Session Error');
	exit;
}

$id = $_SESSION['id'];
$excludeCityMap = (int)$_POST['excludecitymap'];
$excludeUserMap = (int)$_POST['excludeusermap'];

$cityMapSQL = $mysqli->query("
	SELECT
		Name,
		Pos,
		'city' AS Type
	FROM ".DB_LARP.".map_city_data
	WHERE ShowInfoweb = 1");
$cityMapData = $cityMapSQL->fetch_all(MYSQLI_ASSOC);
$userMapSQL = $mysqli->query("
	SELECT
		Name,
		Pos,
		'user' AS Type
	FROM ".DB_LARP.".map_user_data
	WHERE UserID = '$id'");
$userMapData = $userMapSQL->fetch_all(MYSQLI_ASSOC);

$mapData = array_merge($cityMapData, $userMapData);

print(json_encode($mapData));
?>