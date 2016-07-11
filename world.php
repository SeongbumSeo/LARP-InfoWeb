<?php
include "./initial.inc.php";

$time = date("H", strtotime($currenttime));
$serverstatus_background = ($time > 6 && $time < 18) ? "./images/lacityhall_day.jpg" : "./images/lacityhall_night.jpg";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
?>
		<title>World - LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".navl_world").addClass("active");
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
			<div id="conbox" class="loadingstatus" style="background-image: url('./images/magnifier.jpg');">
				<h1 class="chest">Loading...</h1>
				<div style="height: 150px;">&nbsp;</div>
				<p class="smalltime"></p>
			</div>

			<!--<div id="conbox" class="serveronline" style="background-image: url('./weathers/raindrops-window.jpg');">
				<h1 class="chest"><?=$day?>요일<?=$weather?></h1>
				<div style="height: 150px;">&nbsp;</div>
				<p class="smalltime"></p>
			</div>-->
			
			<div id="conbox" class="serveronline" style="background-image: url('<?=$serverstatus_background?>');">
				<h1 class="chest">서버 상태</h1>
				<div id="serverstatus">
					<table class="contable" style="color: #FFF;">
						<tr>
							<th>Address</th>
							<td><?=GAME_HOST?>:<?=GAME_PORT?></td>
						</tr>
						<tr>
							<th>Players</th>
							<td id="gameplayers"></td>
						</tr>
						<tr>
							<th>Mode</th>
							<td id="gamemode"></td>
						</tr>
					</table>
					<table id="playerlist" class="contable" style="color: #FFF;">
					</table>
				</div>
				<div style="height: 10px;">&nbsp;</div>
				<p class="smalltime"></p>
			</div>
			
			<div id="conbox" class="serveroffline" style="background-image: url('./images/construction.jpg');">
				<h1 class="chest">게임 서버 점검중</h1>
				<div style="height: 200px;">&nbsp;</div>
				<p class="smalltime"></p>
			</div>
<?php
include "./footer.inc.php";
?>
		</div>
	</body>
</html>
<?php
mysql_close();
?>