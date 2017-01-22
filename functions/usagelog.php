<?php
session_start();

require_once('mysqli.php');

if(!isset($_SESSION['id'])) {
	print("0");
	exit;
}

$id = $_SESSION['id'];
$username = $_SESSION['username'];
$password = $_SESSION['password'];

$result = $db_log->query("
	SELECT
		CASE LEFT(Type, 2)
			WHEN '칭찬' THEN '#00CC00'
			WHEN '경고' THEN '#CC0000'
			WHEN '차감' THEN '#0000CC'
			ELSE '000'
		END AS TypeColor,
		A.*,
		SUBSTRING_INDEX(Time, ' ', 1) AS Date
	FROM (
		SELECT
			CASE Type
				WHEN 20 THEN '칭찬'
				WHEN 21 THEN '경고'
				WHEN 22 THEN '차감'
				ELSE '기타'
			END AS Type,
			Contents AS Reason,
			Time
		FROM
			log_admin
		WHERE
			IF(
				Type = 21 AND DestID = 0 AND Contents = '악의적 잠수',
				UserID,
				DestID
			) = $id
			AND Type IN (20, 21, 22)
		UNION
		SELECT
			CONCAT(
				SUBSTRING_INDEX(SUBSTRING_INDEX(Contents, '/', 3), '/', -1),
				SUBSTRING_INDEX(SUBSTRING_INDEX(Contents, '/', 4), '/', -1),
				'회'
			) AS Type,
			SUBSTRING_INDEX(SUBSTRING_INDEX(Contents, '/', 5), '/', -1) AS Reason,
			Time
		FROM
			log_infoweb_admin
		WHERE
			Succeed = 1
			AND (
				Contents LIKE '오프라인 프로세스: $id/%%/경고/%%/%%'
				OR Contents LIKE '오프라인 프로세스: $id/%%/차감/%%/%%'
				OR Contents LIKE '오프라인 프로세스: $id/%%/칭찬/%%/%%'
			)
	) A
	ORDER BY Time DESC");

$returns = "1";
while($data = $result->fetch_array()) {
	$returns .= "|".$data['TypeColor'];
	$returns .= "|".$data['Type'];
	$returns .= "|".$data['Reason'];
	$returns .= "|".$data['Date'];
}

print($returns);
?>