<?php
session_start();

require_once("../classes/XmlConstruct.class.php");
require_once('../config.php');
require_once('mysqli.php');

define("CMD_PLAYER",			0);
define("CMD_VEHICLE",			1);

define("ITEM_STATUS_PLAYER",	2);
define("ITEM_STATUS_VEHICLE",	3);

if(!isset($_SESSION['id'])) {
	print("Session Error");
	exit;
}

$cmd = (int)$_POST['cmd'];
$id = $_SESSION['id'];

if($cmd == CMD_PLAYER)
	$result = itemListSQL($mysqli, ITEM_STATUS_PLAYER, $id);
else if($cmd == CMD_VEHICLE)
	$result = itemListSQL($mysqli, ITEM_STATUS_VEHICLE, (int)$_POST['vid']);

$contents = array('NumItems' => $result->num_rows);

$i = 0;
while($data = $result->fetch_array()) {
	$name = $data['Name'];
	$input = $data['Data'];

	$data_type = 0;
	// 1
	if(!strcmp("차 키", $name)) {
		$str_hidden = $data['NumberPlate'];
		if(strpos($input, '+') === strlen($input))
			$str_hidden .= ") (복사된 키";
	}
	// 2
	else if(!strcmp("낚싯바늘", $name) || !strcmp("미끼", $name) || !strcmp("물고기", $name))
		$str_hidden = $input;
	// 3
	else if(!strcmp("낚싯대", $name) || !strcmp("낚싯줄", $name)) {
		$exploded = explode('|', $input);
		$str_name = $exploded[0];
		$str_hidden = sprintf("내구도: %s%%", $exploded[1]);
	}
	// 4
	else if(strpos($input, "LA:") === 0) {
		$exploded = explode(',', $input);
		$str_hidden = sprintf("%s / %s", $exploded[0], $exploded[1]);
	}
	// 5
	else if(!strcmp("라이터", $name))
		$str_hidden = sprintf("%s%%", $input);
	// 7
	else if(!strcmp("방탄복", $name)) {
		$exploded = explode(',', $input);
		$pieces = count($exploded);
		$str_name = sprintf("%s%%", $exploded[0]);
		if($pieces == 2)
			$str_hidden = sprintf("팩션: %s", $exploded[1]);
		else if($pieces == 3)
			$str_hidden = sprintf("%s / %s", $exploded[1], $exploded[2]);
	}
	// 8
	else if(strpos($name ,"음식+") !== false) {
		$exploded = explode('|', $input);
		$time = (int)$exploded[0];
		if($time <= 0)
			$str_hidden = sprintf("%s) (상함", $exploded[1]);
		else
			$str_hidden = sprintf("%s) (유통 기한: %s", $exploded[1], ConvertSecondsToTimeString($time));
	}
	// 9
	else if(!strcmp("실내 가구 설치권", $name))
		$str_hidden = sprintf("%s개", $input);
	// 10
	else if(!strcmp("업그레이드 포인트 상품권", $name))
		$str_hidden = sprintf("%s포인트", $input);
	// 0
	else if(strlen($input))
		$str_hidden = $input;

	if(isset($str_name) && strlen($str_name))
		$name .= "($str_name)";
	if(isset($str_hidden) && strlen($str_hidden))
		$name .= " <span class=\"hidden\">($str_hidden)</span>";

	$contents['Item'.$i] = array(
		'ID' => $data['ID'],
		'Amount' => $data['Amount'].$data['Unit'],
		'Name' => $name
	);

	unset($str_name);
	unset($str_hidden);
	unset($exploded);
	unset($data);
	$i++;
}
$xml = new XmlConstruct('Items');
$xml->fromArray($contents);
$xml->output();

function itemListSQL($db, $status, $statusdata) {
	$result = $db->query("
		SELECT
			a.ID, Name, Unit, Amount,
			IF(
				a.Type IN (108, 109, 110),
				CONCAT(
					TIMESTAMPDIFF(SECOND, NOW(), (
						SELECT Timestamp FROM ".DB_LARP.".item_expire WHERE ItemID = a.ID
					)),
					'|',
					SUBSTRING_INDEX(Data, '|', -1)
				),
				Data
			) Data,
			IF(
				LENGTH(c.NumberPlate) > 0,
				c.NumberPlate,
				\"INVALID\"
			) NumberPlate
		FROM
			".DB_LARP.".item_data a
		INNER JOIN
			".DB_LARP.".item_list b
			ON a.Type = b.Type
		LEFT OUTER JOIN
			".DB_LARP.".car_data c
			ON c.ID = SUBSTRING_INDEX(a.Data, '|', 1)
		WHERE
			Status = $status
			AND StatusData = $statusdata
			AND Amount > 0
		ORDER BY a.ID ASC");
	return $result;
}
?>