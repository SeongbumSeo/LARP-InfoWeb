<?php
session_start();

require_once("../classes/XmlConstruct.class.php");
require_once('../config.php');
require_once('mysqli.php');

if(!isset($_SESSION['id'])) {
	print("Session Error");
	exit;
}

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

$result = $mysqli->query("
	SELECT
		CASE LEFT(Type, 2)
			WHEN '칭찬' THEN '#33CC33'
			WHEN '경고' THEN '#CC3333'
			WHEN '차감' THEN '#3333CC'
			ELSE '#333'
		END TypeColor,
		A.*,
		SUBSTRING_INDEX(Time, ' ', 1) Date
	FROM (
		SELECT
			CASE Type
				WHEN 20 THEN '칭찬'
				WHEN 21 THEN '경고'
				WHEN 22 THEN '차감'
				ELSE '기타'
			END Type,
			Contents,
			Time
		FROM
			".DB_LARP_LOG.".log_admin
		WHERE
			IF(
				Type = 21 AND DestID = 0 AND Contents = '악의적 잠수',
				UserID,
				DestID
			) = $id
			AND Type IN (20, 21, 22)

		UNION SELECT
			CONCAT(
				SUBSTRING_INDEX(SUBSTRING_INDEX(Contents, '/', 3), '/', -1),
				SUBSTRING_INDEX(SUBSTRING_INDEX(Contents, '/', 4), '/', -1),
				'회'
			) Type,
			SUBSTRING_INDEX(SUBSTRING_INDEX(Contents, '/', 5), '/', -1) Contents,
			Time
		FROM
			".DB_LARP_LOG.".log_infoweb_admin
		WHERE
			Succeed = 1
			AND (
				Contents LIKE '오프라인 프로세스: $id/%%/경고/%%/%%'
				OR Contents LIKE '오프라인 프로세스: $id/%%/차감/%%/%%'
				OR Contents LIKE '오프라인 프로세스: $id/%%/칭찬/%%/%%'
			)

		UNION SELECT
			'개명' Type,
			SUBSTRING_INDEX(Contents, '(', 1) Contents,
			Time
		FROM
			".DB_LARP_LOG.".log_user
		WHERE
			UserID = $id
			AND Type = 5

		UNION SELECT
			'가입' Type,
			CONCAT(
				'LA:RP에 가입하신 지 ',
				TO_DAYS(NOW()) - TO_DAYS(CreatedTime),
				'일 되었습니다.'
			) Contents,
			CreatedTime Time
		FROM
			".DB_LARP.".user_data
		WHERE
			ID = $id
	) A
	ORDER BY Time DESC");

$contents = array('NumRows' => $result->num_rows);

$i = 0;
while($data = $result->fetch_array()) {
	$returns .= "|".$data['TypeColor'];
	$returns .= "|".$data['Type'];
	$returns .= "|".$data['Contents'];
	$returns .= "|".$data['Date'];

	$contents['Row'.$i] = array(
		'TypeColor' => $data['TypeColor'],
		'Type' => $data['Type'],
		'Contents' => $data['Contents'],
		'Date' => $data['Date']
	);

	unset($data);
	$i++;
}
$xml = new XmlConstruct('UsageLog');
$xml->fromArray($contents);
$xml->output();
?>