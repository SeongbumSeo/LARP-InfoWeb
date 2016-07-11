<?php
include "./initial.inc.php";

// In Character
$age = $UserData['Age'];
$level = $UserData['Level'];
$phonenumber = $UserData['PhoneNumber'];
// Out Of Character
$respect = $UserData['Respect'];
$upgradepoint = $UserData['UpgradePoint'];
$warns = $UserData['Warns'];
$praises = $UserData['Praises'];

// 팩션 & 직업
$faction = GetFactionName($UserData['Faction'],$DB);
$job = GetJobName($UserData['Job']);

// 전화번호
if($UserData['PhoneNumber'] == 0)
	$pnumber = "없음";
else
	$pnumber = $UserData['PhoneNumber'];

// 최종 위치
$lastpos = explode(',',$UserData['LastPos']);
$pos_x = $lastpos[0];
$pos_y = $lastpos[1];
$pos_z = $lastpos[2];
$angle = $lastpos[3];
$interior = $lastpos[4];
$virtualworld = $lastpos[5];
if($interior != 0 || $virtualworld != 0)
	$location = "추적불가";
else
	$location = GetLocationName($pos_x,$pos_y,$interior,$virtualworld);

// 현금,통장 잔액
$money = number_format($UserData['Money']);
$bank = number_format($UserData['Bank']);

// 자격증
$license_drive =	($UserData['License_Drive'] >= 1)		? "./licenses/drive.jpg"	: "./licenses/drive_dark.jpg";
$license_aviation =	($UserData['License_Aviation'] >= 1)	? "./licenses/aviation.jpg"	: "./licenses/aviation_dark.jpg";
$license_sailing =	($UserData['License_Sailing'] >= 1)		? "./licenses/sailing.jpg"	: "./licenses/sailing_dark.jpg";
/*$license_drive =	($UserData['License_Drive'] >= 1)		? "color: #000;"			: "color: #FFF; text-shadow: none;";
$license_aviation =	($UserData['License_Aviation'] >= 1)	? "color: #000;"			: "color: #FFF; text-shadow: none;";
$license_sailing =	($UserData['License_Sailing'] >= 1)		? "color: #000;"			: "color: #FFF; text-shadow: none;";*/
$penalty_drive =    $UserData['Penalty_Drive'];
$penalty_aviation = $UserData['Penalty_Aviation'];
$penalty_sailing =  $UserData['Penalty_Sailing'];

/* 차량 */
$query = mysql_query("SELECT * FROM car_data WHERE OwnerType = 1 AND OwnerID = '".$UserData['ID']."'",$DB);
$NumCars = mysql_num_rows($query);
for($c = 0; $CarData = mysql_fetch_array($query); $c++) {
	// 이미지
	$CarImg[$c] = GetVehicleImage($CarData['Model']);
	
	// 모델,번호판,시동,상태,블로우,잠금여부,체력,연료
	$CarModel[$c] = GetVehicleName($CarData['Model']);
	$CarNumberPlate[$c] = $CarData['NumberPlate'];
	$CarEngine[$c] = ($CarData['Engine']) ? "켜져있음" : "꺼져있음";
	$CarStatus[$c] = ($CarData['Active']) ? "꺼내져있음" : "넣어져있음";
	$CarBlowedCnt[$c] = $CarData['BlowedCnt'];
	$CarLocked[$c] = ($CarData['Locked']) ? "잠겨있음" : "열려있음";
	$CarHealth[$c] = floor($CarData['Health']);
	$CarFuel[$c] = floor($CarData['Fuel'] * 100 / 10000000);

	// 최종 위치
	if($CarData['Towed'])
		$CarLocation[$c] = "<u>견인 차량 보관소</u>";
	else if($CarData['Blowed'])
		$CarLocation[$c] = "<u>파괴 차량 보관소</u>";
	else {
		$lastpos = explode(',',$CarData['LastPos']);
		$pos_x = $lastpos[0];
		$pos_y = $lastpos[1];
		$pos_z = $lastpos[2];
		$angle = $lastpos[3];
		$interior = $lastpos[4];
		$virtualworld = $lastpos[5];
		if($virtualworld >= 40000 && $virtualworld < 50000) {
			$plquery = mysql_query("SELECT Name FROM parkinglot_data WHERE ID = ".($virtualworld-40000),$DB);
			if(mysql_num_rows($plquery) < 1)
				$CarLocation[$c] = "추적불가";
			else
				$CarLocation[$c] = mysql_result($plquery,0);
		}
		else if($interior != 0 || $virtualworld != 0)
			$CarLocation[$c] = "추적불가";
		else
			$CarLocation[$c] = GetLocationName($pos_x,$pos_y);
	}
}

/* 아이템 */
/*$query = mysql_query(sprintf("
	SELECT
		a.ID,Name,Unit,Amount,
		IF(
			Type IN (108,109,110),
			CONCAT(
				TIMESTAMPDIFF(SECOND,NOW(),(SELECT Timestamp FROM item_data_timestamp WHERE ItemID = a.ID)),
				'|',
				SUBSTRING_INDEX(HiddenData,'|',-1)
			),
			HiddenData
		) HiddenData
	FROM item_data a INNER JOIN item_list b ON a.Type = b.ID
	WHERE Status=2 AND StatusData='%s'
	",$UserData['Username']),$DB);*/
$query = mysql_query(sprintf("
	SELECT 
		a.ID,Name,Unit,Amount,
		IF(
			a.Type IN (108,109,110),
			CONCAT(
				TIMESTAMPDIFF(SECOND,NOW(),(SELECT Timestamp FROM item_expire WHERE ItemID = a.ID)),
				'|',
				SUBSTRING_INDEX(Data,'|',-1)
			),
			Data
		) Data 
	FROM item_data a INNER JOIN item_list b ON a.Type = b.Type 
	WHERE Status = 2 AND StatusData = %d AND Amount > 0
    ",$UserData['ID']),$DB);
$NumItems = mysql_num_rows($query);
for($i = 0; $ItemData = mysql_fetch_array($query); $i++) {
	$type = $ItemData['Name'];
	$input = $ItemData['Data'];

	/*
		0: 출력 안함
		1: DB ID -> Number Plate
		2: 그대로 출력
		3: Name|100 -> 내구도: 100%
		4: A,B -> A / B
		5: 100 -> 100%
		6: 100 -> ID: 100
		7: A,B,C -> B / C 또는 A,B -> B
		8: 123|음식명 -> (음식명) (유통 기한: 0분 0초)
	*/

	// 1
	if(!strcmp($type,"차 키")) {
		if(strpos($input,'+') === strlen($input))
			$str_hidden = ") (복사된 키";
		else {
			$cquery = mysql_query(sprintf("SELECT NumberPlate FROM car_data WHERE ID='%s'",intval($input)),$DB);
			$str_hidden = (mysql_num_rows($cquery) < 1)? "INVALID": mysql_result($cquery,0);
		}
	}
	// 2
	else if(!strcmp($type,"낚싯바늘") || !strcmp($type,"미끼") || !strcmp($type,"물고기")) {
		$str_hidden = $input;
	}
	// 3
	else if(!strcmp($type,"낚싯대") || !strcmp($type,"낚싯줄")) {
		$exploded = explode('|',$input);
		$name = $exploded[0];
		$str_hidden = sprintf("내구도: %s%%",$exploded[1]);
	}
	// 4
	else if(strpos($input,"LA:") === 0) {
		$exploded = explode(',',$input);
		$str_hidden = sprintf("%s / %s",$exploded[0],$exploded[1]);
	}
	// 5
	else if(!strcmp($type,"라이터")) {
		$str_hidden = sprintf("%s%%",$input);
	}
	// 6
	else if(!strcmp($type,"C4 리모컨")) {
		$str_hidden = sprintf("ID: %s",$input);
	}
	// 7
	else if(!strcmp($type,"방탄복")) {
		$exploded = explode(',',$input);
		$pieces = count($exploded);
		$name = sprintf("%s%%",$exploded[0]);
		if($pieces == 2)
			$str_hidden = sprintf("팩션: %s",$exploded[1]);
		else if($pieces == 3)
			$str_hidden = sprintf("%s / %s",$exploded[1],$exploded[2]);
	}
	// 8
	else if(strpos($type,"음식+") !== false) {
		$exploded = explode('|',$input);
		$time = (int)$exploded[0];
		if($time <= 0)
			$str_hidden = sprintf("%s) (상함",$exploded[1]);
		else
			$str_hidden = sprintf("%s) (유통 기한: %s",$exploded[1],ConvertSecondsToTimeString($time));
	}
	// 0
	else if(strlen($input) > 0)
		$str_hidden = $input;

	$ItemID[$i] = $ItemData['ID'];
	$ItemName[$i] = $type;
	if(isset($name))
		$ItemName[$i] .= sprintf("(%s)",$name);
	if(isset($str_hidden))
		$ItemName[$i] .= sprintf(" <span style=\"color: #AAA;\">(%s)</span>",$str_hidden);

	$ItemAmount[$i] = $ItemData['Amount'].$ItemData['Unit'];

	unset($name);
	unset($str_hidden);
	unset($exploded);
}

/* 사용자 로그 */
$query = mysql_query(sprintf("
	SELECT
		IF (
			Type = 20,
			'칭찬',
			IF (
				Type = 21,
				'경고',
				'차감'
			)
		) AS Title,
		Contents AS Reason,
		Time
	FROM
		log_admin
	WHERE
		IF (
			Type = 21 AND DestID = 0 AND Contents = '악의적 잠수',
			UserID,
			DestID
		) = %d
		AND Type IN (20,21,22)
	UNION
	SELECT
		CONCAT (
			SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,'/',3),'/',-1),
			SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,'/',4),'/',-1),
			'회'
		) AS Title,
		SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,'/',5),'/',-1) AS Reason,
		Time
	FROM
		log_infoweb_admin
	WHERE
		Succeed = 1
		AND (
			Contents LIKE '오프라인 프로세스: %d/%%/경고/%%/%%'
			OR Contents LIKE '오프라인 프로세스: %d/%%/차감/%%/%%'
			OR Contents LIKE '오프라인 프로세스: %d/%%/칭찬/%%/%%'
		)
	ORDER BY Time DESC
    ",$UserData['ID'],$UserData['ID'],$UserData['ID'],$UserData['ID']),$LOG);
$NumUserLogs = mysql_num_rows($query);
for($i = 0; $UserLogDataTmp = mysql_fetch_array($query); $i++)
	$UserLogData[$i] = array(
		'Type' => $UserLogDataTmp['Title'],
		'Reason' => $UserLogDataTmp['Reason'],
		'Time' => $UserLogDataTmp['Time'],
		'Date' => explode(' ',$UserLogDataTmp['Time'])[0]);
/*$query = mysql_query(sprintf("
	SELECT
		SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,':',-1),'/',1) AS DestID,
		SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,'/',2),'/',-1) AS DestName,
		CONCAT (
			SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,'/',3),'/',-1),
			SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,'/',4),'/',-1),
			'회'
		) AS Type,
		SUBSTRING_INDEX(SUBSTRING_INDEX(Contents,'/',5),'/',-1) AS Reason
	FROM
		log_infoweb_admin
	WHERE
		Succeed = 1
		AND (
			Contents LIKE '%d/%%/경고/%%/%%'
			OR Contents LIKE '%d/%%/차감/%%/%%'
			OR Contents LIKE '%d/%%/칭찬/%%/%%'
		)
",$UserData['ID'],$UserData['ID'],$UserData['ID']),$LOG);
$NumUserLogs += mysql_num_rows($query);
for($i; $UserLogDataTmp = mysql_fetch_array($query); $i++) {
	unset($exploded);
	unset($value);
	
	// ID / Username / Function / Value / Reason
	$exploded = explode("/",explode("오프라인 프로세스: ",$UserLogDataTmp['Contents'])[1]);
	$value = ((int)$exploded[3] > 1)? sprintf("%d회",(int)$exploded[3]): null;
	
	$UserLogData[$i] = array(
		'Type' => sprintf("%s%s",$exploded[2],$value),
		'Reason' => $exploded[4],
		'Time' => $UserLogDataTmp['Time'],
		'Date' => explode(' ',$UserLogDataTmp['Time'])[0]);
}*/
for($i = 0; $i < $NumUserLogs; $i++) {
	for($j = $i; $j < $NumUserLogs; $j++)
		if(strtotime($UserLogData[$i]['Time']) < strtotime($UserLogData[$j]['Time'])) {
			unset($tmp);
			$tmp = $UserLogData[$i];
			$UserLogData[$i] = $UserLogData[$j];
			$UserLogData[$j] = $tmp;
		}
	
	switch(substr($UserLogData[$i]['Type'],0,6)) {
		case '칭찬': // 칭찬(녹색)
			$UserLogData[$i]['BackgroundColor'] = "F0FFF0";
			$UserLogData[$i]['TypeColor'] = "00CC00";
			break;
		case '경고': // 경고(적색)
			$UserLogData[$i]['BackgroundColor'] = "FFF0F0";
			$UserLogData[$i]['TypeColor'] = "CC0000";
			break;
		case '차감': // 차감(청색)
			$UserLogData[$i]['BackgroundColor'] = "F0F0FF";
			$UserLogData[$i]['TypeColor'] = "0000CC";
			break;
		default: // 기타
			$UserLogData[$i]['BackgroundColor'] = "FFF";
			$UserLogData[$i]['TypeColor'] = "000";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
?>
		<title>Profile - LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".navl_profile").addClass("active");
			});
		</script>
	</head>

	<body>
<?php
include "./navbar.inc.php";
?>
		<div id="contents">
<?php
include "./header.inc.php";
?>
			<div id="conbox" class="head" style="background-color: #DDDDAA;">
				<h1><?=$username?></h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFFFF0;">
				<table class="contable">
					<tr>
						<td class="charimg" style="background-image: url('<?=GetSkinImage($UserData['Skin'])?>');">
						</td>
						<td>
							<h2>정보</h2>
							<div id="detail">
								<h3>In Character</h3>
								<p>
									<span><b>나이</b><?=$age?>세</span>
									<span><b>신용등급</b><?=$level?></span>
									<span><b>전화번호</b><?=$phonenumber?></span>
									<span><b>국적</b><?=$origin?></span>
									<span><b>팩션</b><?=$faction?></span>
									<span><b>직업</b><?=$job?></span>
								</p>
								<h3>Out Of Character</h3>
								<p>
									<span><b>존경치</b><?=$respect?>/<?=(($level+1)*4)?></span>
									<span><b>업글포인트</b><?=$upgradepoint?></span>
									<span><b>경고</b><?=$warns?>/7</span>
									<span><b>칭찬</b><?=$praises?>/3</span>
								</p>
							</div>
							<h2>위치</h2>
							<div id="detail">
								<p><?=$location?></p>
							</div>
							<h2>소지금</h2>
							<div id="detail">
								<p>
									<span><b>현금</b>$<?=$money?></span>
									<span><b>통장 잔액</b>$<?=$bank?></span>
								</p>
							</div>
						</td>
					</tr>
				</table>
				<div style="height: 10px;">&nbsp;</div>
				<p class="smalltimestopped"></p>
			</div>
			
			<div id="conbox" class="playeronline" style="background-color: #5F5F5F; color: #FFF; border-radius: 0;">
				<h2>LA:RP에 <span style="color: #E0FFE0;">접속중</span>이시군요!</h2>
				<p>&nbsp;</p>
				<p>인게임에서 데이터를 <span style="color: #FFFFDD; font-weight: bold;">저장</span>하고 오시면 더 정확한 정보가 표시됩니다.</p>
			</div>
			
			<div id="conbox" class="head" style="background-color: #AAAADD;">
				<h1>자격증 및 벌점</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<table class="contable">
					<tr class="licenses">
						<td style="background-image: url('<?=$license_drive?>');">		<!-- 운전면허 -->
							<p><?=$penalty_drive?></p>
						</td>
						<td style="background-image: url('<?=$license_aviation?>');">	<!-- 항공면허 -->
							<p><?=$penalty_aviation?></p>
						</td>
						<td style="background-image: url('<?=$license_sailing?>');">	<!-- 항해면허 -->
							<p><?=$penalty_sailing?></p>
						</td>
					</tr>
				</table>
			</div>
			
<?php
if($NumCars > 0) {
?>
			<div id="conbox" class="head" style="background-color: #AAAADD;">
				<h1>차량</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<table class="contable">
<?php
	for($c = 0; $c < $NumCars; $c++) {
?>
					<tr>
						<td class="carimg" style="background-image: url('<?=$CarImg[$c]?>');">
						</td>
						<td>
							<h2><?=$CarModel[$c]?>&nbsp;</h2>
							<div id="detail">
								<p>
									<span><b>번호판</b><?=$CarNumberPlate[$c]?></span>
									<span><b>시동</b><?=$CarEngine[$c]?></span>
									<span><b>상태</b><?=$CarStatus[$c]?></span>
									<span><b>블로우</b><?=$CarBlowedCnt[$c]?>회</span>
									<span><b>잠금여부</b><?=$CarLocked[$c]?></span>
									<span><b>체력</b><?=$CarHealth[$c]?></span>
									<span><b>연료</b><?=$CarFuel[$c]?>%</span>
									<span><b>위치</b><?=$CarLocation[$c]?></span>
								</p>
								<p>&nbsp;</p>
							</div>
						</td>
					</tr>
<?php
	}
?>
				</table>
			</div>
<?php
}
if($NumItems > 0) {
?>
			<div id="conbox" class="head" style="background-color: #AAAADD;">
				<h1>아이템</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<table class="itemtable">
					<tr>
						<th>ID</th>
						<th>소지량</th>
						<th>이름</th>
					</tr>
<?php
	for($i = 0; $i < $NumItems; $i++) {
?>
					<tr>
						<td><?=$ItemID[$i]?></td>
						<td><?=$ItemAmount[$i]?></td>
						<td><?=$ItemName[$i]?></td>
					</tr>
<?php
	}
?>
				</table>
			</div>
<?php
}
if($NumUserLogs > 0) {
?>
			<div id="conbox" class="head" style="background-color: #AAAADD;">
				<h1>사용자 로그</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<table class="userlogtable">
					<tr>
						<th>분류</th>
						<th>사유</th>
						<th>날짜</th>
					</tr>
<?php
	for($i = 0; $i < $NumUserLogs; $i++) {
?>
					<tr style="background-color: #<?=$UserLogData[$i]['BackgroundColor']?>;">
						<td style="color: <?=$UserLogData[$i]['TypeColor']?>"><?=$UserLogData[$i]['Type']?></td>
						<td><?=$UserLogData[$i]['Reason']?></td>
						<td><?=$UserLogData[$i]['Date']?></td>
					</tr>
<?php
	}
?>
				</table>
			</div>
<?php
}
include "./footer.inc.php";
?>
		</div>
	</body>
</html>
<?php
mysql_close();
?>