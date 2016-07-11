<?php
$result = mysql_query("SELECT * FROM infoweb_ban_data WHERE IP = '".$_SERVER['REMOTE_ADDR']."' ORDER BY Until DESC LIMIT 1",$DB);
$BanData = mysql_fetch_array($result);
$bancount = strtotime($data['Until'])-strtotime(GetDBServerTime($DB));
if(mysql_num_rows($result) > 0 && $bancount > 0)
{
	session_unset();
	session_destroy();
	
	/*$total_secs = abs($bancount);
	$diff_in_days = floor($total_secs / 86400);
	$rest_hours = $total_secs % 86400;
	$diff_in_hours = floor($rest_hours / 3600);
	$rest_mins = $rest_hours % 3600;
	$diff_in_mins = floor($rest_mins / 60);
	$diff_in_secs = floor($rest_mins % 60);
	
	if($diff_in_days > 0)
		$banuntil .= $diff_in_days."일 ";
	if($diff_in_hours > 0)
		$banuntil .= $diff_in_hours."시간 ";
	if($diff_in_mins > 0)
		$banuntil .= $diff_in_mins."분 ";
	$banuntil .= $diff_in_secs."초";*/
	$banuntil = ConvertSecondsToTimeString($bancount);
	$banreason = $BanData['Reason'];
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
	include "./head.inc.php";
?>
		<title>접근차단 - LA:RP 인포웹</title>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$(".navl_login").css("display","inline");
				$(".navl_login").addClass("active");
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
			<div id="conbox" style="background-color: #FFF; color: #FF0000;">
				<p>접근이 차단되었습니다.</p>
				<p>이유: <?=$banreason?>,남은 시간: <?=$banuntil?></p>
			</div>
<?php
	include "./footer.inc.php";
?>
		</div>
	</body>
</html>
<?php
	exit;
}
$exploded = explode('.',$_SERVER['REMOTE_ADDR']);
$sql = sprintf("
	SELECT Reason,Host,Time,Until,if(Until = '0000-00-00 00:00:00',1,0) AS Permanent FROM ban_data
	WHERE Valid = 1 AND (Until = '0000-00-00 00:00:00' OR Until > NOW())
	AND (Username = '%s' OR (IDBan = 0 AND (IP = '%s.%s.%s.%s' OR IP = '%s.%s.%s.*' OR IP = '%s.%s.*.*')))
", $UserData['Username'], $exploded[0],$exploded[1],$exploded[2],$exploded[3], $exploded[0],$exploded[1],$exploded[2], $exploded[0],$exploded[1]);
$bquery = mysql_query($sql,$DB);
if(mysql_num_rows($bquery) > 0) {
	$BanData = mysql_fetch_array($bquery);
	$until = ($BanData['Permanent']) ? "무기한" : $BanData['Until'];
?>
<div id="conbox" style="background-color: #FFF; color: #FF0000;">
	<p>서버에 접근이 금지되어 있습니다!</p>
	<span style="color: #000;">
		<p>* 사유: <?=$BanData['Reason']?></p>
		<p>* 담당자: <?=$BanData['Host']?></p>
		<p>* 처분 시각: <?=$BanData['Time']?></p>
		<p>* 해제 예정일: <?=$until?></p>
	</span>
</div>
<?php
	require("footer.inc.php");
	exit;
}
?>