
<?php
if(IsUserLoggedIn()) {
	// 국적
	switch($UserData['Origin'])
	{
		case 0: $origin = "미국"; $frag = "USA"; $fragimg = "./frags/usa.png"; break;
		case 1: $origin = "한국"; $frag = "KOR"; $fragimg = "./frags/korea.png"; break;
		case 2: $origin = "이탈리아"; $frag = "ITA"; $fragimg = "./frags/italy.png"; break;
		case 3: $origin = "일본"; $frag = "JPN"; $fragimg = "./frags/japan.png"; break;
		case 4: $origin = "스페인"; $frag = "ESP"; $fragimg = "./frags/spain.png"; break;
		case 5: $origin = "러시아"; $frag = "RUS"; $fragimg = "./frags/rusia.png"; break;
		case 6: $origin = "프랑스"; $frag = "FRA"; $fragimg = "./frags/france.png"; break;
		case 7: $origin = "중국"; $frag = "CHN"; $fragimg = "./frags/china.png"; break;
		case 8: $origin = "이라크"; $frag = "IRQ"; $fragimg = "./frags/iraq.png"; break;
		case 9: $origin = "독일"; $frag = "GER"; $fragimg = "./frags/germany.png"; break;
		case 10: $origin = "영국"; $frag = "GBR"; $fragimg = "./frags/uk.png"; break;
		default: $origin = "미상"; $frag = "UKN"; $fragimg = "./frags/unknown.png";
	}
	// 온라인 상태
	/*$query = "SELECT * FROM server_info WHERE Type = 'Player' AND Value LIKE '%|".$UserData['Username']."|%'";
	$query .= " AND (SELECT Value FROM server_info WHERE Type = 'Version') NOT LIKE '%dev%'";
	$query .= " AND DATE_ADD((SELECT Value FROM server_info WHERE Type = 'LastUpdate'),INTERVAL 30 SECOND) > NOW()";
	$result = mysql_query($query,$DB);
	$isOnline = (mysql_num_rows($result) > 0);
	$onlineimg = ($isOnline) ? "./images/online.png" : "./images/offline.png";*/
	$onlineimg = "./images/online.png";
	$offlineimg = "./images/offline.png";
?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".navbar-right li,.navl_home,.navl_profile,.navl_faction,.navl_world,.navl_admin,.navl_history,.navl_forum").css("display","inline");
	});
</script>
<?php
}
else {
?>
<script type="text/javascript">
	$(document).ready(function() {
		$(".navl_login,.navl_forum").css("display","inline");
	});
</script>
<?php
}
?>

<div id="navbar">
	<ul class="navbar-right">
		<li class="navr_logout">
			<a href="?logout=true">Log Out</a>
		</li>
		<li class="navr_username">
			<img class="frag playeronline" src="<?=$onlineimg?>" />
			<img class="frag playeroffline" src="<?=$offlineimg?>" />
			<?=$username?>
		</li>
		<li class="navr_frag">
			<img class="frag" src="<?=$fragimg?>" />
			<?=$frag?>
		</li>
	</ul>
	<ul class ="navbar-left">
		<li class="navl_home">
			<a href="index.php">Home</a>
		</li>
		<li class="navl_profile">
			<a href="profile.php">Profile</a>
		</li>
		</li>
		<li class="navl_world">
			<a href="world.php">World</a>
		</li>
		<!--<li class="navl_faction">
			<a href="faction.php">Faction</a>
		</li>-->
		<li class="navl_feedback">
			<a href="feedback.php">Feedback</a>
		</li>
		<li class="navl_admin">
			<a href="admin.php">Admin</a>
		</li>
		<li class="navl_history">
			<a href="history.php">History</a>
		</li>
		<li class="navl_login">
			<a href="login.php">Log In</a>
		</li>
		<li class="navl_signup">
			<a href="signup.php">Sign Up</a>
		</li>
		<li class="navl_forum">
			<a href="http://cafe.daum.net/LARP" target="_blank">Forum</a>
		</li>
	</ul>
</div>