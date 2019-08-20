<?php
session_start();

require_once('../config.php');
require_once('mysqli.php');
require_once('getlocationname.php');

if(!isset($_SESSION['id'])) {
	print("Session Error");
	exit;
}

$id = (int)$_SESSION['id'];
$vid = (int)$_POST['vid'];

$result = $mysqli->query("
	SELECT
		IF(
			a.UserID = $id,
			'본인',
			'타인'
		) User,
		a.Pos,
		a.Reverted,
		Time
	FROM
		".DB_LARP_LOG."._log_carblow a
	INNER JOIN
		".DB_LARP.".car_data b
		ON a.CarID = b.ID
	WHERE
		b.ID = $vid
		AND b.OwnerType = 1
		AND b.OwnerID = $id
	ORDER BY a.Time DESC");

$i = 0;
$contents = array();
while($data = $result->fetch_array()) {
	$position = explode(',', $data['Pos']);
	$location = getLocationName($position[0], $position[1], $position[3], $position[4]);
	$trackable = $position[3] != 0 || $position[4] != 0 ? 0 : 1;
	$position = $trackable == 1 ? $position : array(0, 0);

	$contents[$i++] = array(
		'User' => $data['User'],
		'PositionX' => $position[0],
		'PositionY' => $position[1],
		'Trackable' => $trackable,
		'Location' => $location,
		'Reverted' => $data['Reverted'],
		'Time' => $data['Time']
	);

	unset($data);
	unset($position);
	unset($location);
	unset($trackable);
}

print(json_encode($contents));
?> 