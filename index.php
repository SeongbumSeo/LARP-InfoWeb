<?php
require("config.php");
require("classes/Notice.class.php");
?>
<!doctype html>
<html class="no-js" lang="ko">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
	<title>LA:RP Information Website</title>
	<link rel="stylesheet" href="css/foundation.min.css">
	<link rel="stylesheet" href="css/app.css">
</head>

<body>
	<div class="top-bar-background"></div>
	<div class="top-bar">
		<div class="row">
			<div class="top-bar-title">
				<a href="#"><img src="images/logos/white_gray.png" /></a>
				<span class="menu-icon-container" data-responsive-toggle="responsive-menu" data-hide-for="medium">
					<button class="menu-icon" type="button" data-toggle></button>
				</span>
			</div>
			<div id="responsive-menu">
				<div class="top-bar-right">
					<ul class="menu" data-magellan data-bar-offset="30">
						<li><a href="#notice">Notice</a></li>
						<li class="show-signedin"><a href="#profile">Profile</a></li>
						<li class="show-signedin"><a href="#world">World</a></li>
						<li class="show-signedin"><a href="./old/admin.php">Admin</a></li>
						<li><a class="signin-button" href="#signin"></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="orbit topOrbit" role="region" data-orbit data-auto-play="false">
		<ul class="orbit-container">
			<li class="orbit-slide is-active">
				<img src="images/posters/Nightsky.jpg" class="poster" />
			</li>
			<!--<button class="orbit-previous" aria-label="previous"><span class="show-for-sr">Previous Slide</span>&#9664;</button>
			<button class="orbit-next" aria-label="next"><span class="show-for-sr">Next Slide</span>&#9654;</button>
			<li class="orbit-slide is-active">
				<video poster="images/posters/shining_sun.jpg" class="poster">
					<source src="videos/shining_sun.mp4" type="video/mp4" />
				</video>
			</li>
			<li class="orbit-slide">
				<video poster="images/posters/여자친구(GFRIEND) - 너 그리고 나 (NAVILLERA).jpg" class="poster">
					<source src="videos/여자친구(GFRIEND) - 너 그리고 나 (NAVILLERA).mp4" type="video/mp4" />
				</video>
			</li>
			<li class="orbit-slide">
				<video poster="images/posters/Adam Levine - Lost Stars.jpg" class="poster">
					<source src="videos/Adam Levine - Lost Stars.mp4" type="video/mp4" />
				</video>
			</li>-->
		</ul>
	</div>

	<div id="content-wrapper" class="topAnchor">

		<div id="notice" class="row content">
			<div class="small-12 medium-6 columns">
				<h1>Notice</h1>
<?php
$notice_array = new Notice(URL_NOTICE, DATE_INDEX_NOTICE, MAX_NOTICES);
for($i = 0; $i < MAX_NOTICES; $i++) {
	$subject = $notice_array->getNotice()[$i]['subject'];
	$date = $notice_array->getNotice()[$i]['date'];
	if(strcmp($subject, "&nbsp;") != 0 && strlen($subject) > 0) {
?>
				<div class="row">
					<div class="small-9 columns">
						<a><?=$subject?></a>
					</div>
					<div class="small-3 columns"><?=$date?></div>
				</div>
<?php
	}
}
?>
			</div>
			<div class="small-12 medium-6 columns">
				<h1>Events</h1>
<?php
$event_array = new Notice(URL_EVENT, DATE_INDEX_EVENT, MAX_NOTICES);
for($i = 0; $i < MAX_NOTICES; $i++) {
	$subject = $event_array->getNotice()[$i]['subject'];
	$date = $event_array->getNotice()[$i]['date'];
	if(strcmp($subject, "&nbsp;") != 0 && strlen($subject) > 0) {
?>
				<div class="row">
					<div class="small-9 columns">
						<a><?=$subject?></a>
					</div>
					<div class="small-3 columns"><?=$date?></div>
				</div>
<?php
	}
}
?>
			</div>
		</div>

		<hr />

		<div id="signin" class="content hide-signedin hide">
			<div class="row">
				<div class="column">
					<h1>Sign in with your <u>nickname</u></h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 large-7 medium-centered columns" style="text-align: center;">
					<div class="signin-alert alert callout" data-closable="slide-out-right" style="display: none;">
						<p></p>
						<button class="close-button" aria-label="Dismiss alert" type="button" data-close>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 large-7 medium-centered columns">
					<div class="input-group">
						<span class="input-group-label">Nickname</span>
						<input class="input-group-field username" type="text" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 large-7 medium-centered columns">
					<div class="input-group">
						<span class="input-group-label">Password</span>
						<input class="input-group-field password" type="password" />
						<div class="input-group-button">
							<input type="submit" class="button signin-submit" value="Sign in" />
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="items" class="reveal show-signedin hide" data-reveal>
			<h3></h3>
			<table>
				<thead>
					<tr>
						<th width="15%">ID</th>
						<th width="15%">소지량</th>
						<th width="70%">이름</th>
					</tr>
				</thead>
				<tbody class="item-data">
					<tr class="hide">
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<button class="close-button" data-close type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div id="usagelog" class="reveal show-signedin hide" data-reveal>
			<h3>이용 로그</h3>
			<table>
				<thead>
					<tr>
						<th>분류</th>
						<th>사유</th>
						<th>날짜</th>
					</tr>
				</thead>
				<tbody class="usagelog-data">
					<tr class="hide">
						<td></td>
						<td></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<button class="close-button" data-close type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div id="profile" class="content show-signedin hide">
			<div class="row">
				<div class="column">
					<h1>Profile</h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 medium-centered columns">
					<div class="row">
						<div class="small-12 large-4 columns img">
							<img />
						</div>

						<div class="small-12 large-8 columns">
							<span class="secondary label party hide"></span>
							<h3>
								<span class="username"></span>
								<span class="badge level"></span>
							</h3>
							<div class="success progress">
								<div class="progress-meter hunger"></div>
							</div>
							<div class="alert progress">
								<div class="progress-meter health"></div>
							</div>
							<div class="status-details-wrapper">
								<div class="status-details">
								</div>
								<div class="expanded button-group">
									<button type="button" class="show-item-list button">아이템 목록</button>
									<button type="button" class="show-map button disabled">상세 위치</button>
									<button type="button" class="show-usagelog button">이용 로그</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="vehicles" class="content show-signedin hide">
			<div class="row">
				<div class="column">
					<h1>Vehicles</h1>
				</div>
			</div>
			<div class="row each-vehicle">
				<div class="small-12 large-6 columns hide vblock">
					<div class="row">
						<div class="small-12 medium-4 columns img">
							<img />
						</div>

						<div class="small-12 medium-8 columns">
							<h3></h3>
							<div class="progress">
								<div class="progress-meter fuel"></div>
							</div>
							<div class="alert progress">
								<div class="progress-meter health"></div>
							</div>
							<div class="status-details-wrapper">
								<div class="status-details">
								</div>
								<div class="expanded button-group">
									<button type="button" class="show-item-list button">아이템 목록</button>
									<button type="button" class="show-map button disabled">상세 위치</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="small-12 hide-for-large columns hide vmargin">
				</div>
			</div>
		</div>

		<div id="world" class="content show-signedin hide">
			<div class="row">
				<div class="column">
					<h1>World</h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-7 large-5 medium-centered columns">
					<div class="serverstatus-loading hide">Loading...</div>
					<div class="serverstatus-offline hide">게임 서버 점검중</div>
					<div class="serverstatus-online hide">
						<table class="serverinfo">
							<tr>
								<th>주소</th>
								<td><?=GAME_HOST?>:<?=GAME_PORT?></td>
							</tr>
							<tr>
								<th>인원</th>
								<td class="gameplayers"></td>
							</tr>
							<tr>
								<th>버전</th>
								<td class="gamemode"></td>
							</tr>
						</table>
						<table class="playerlist">
						</table>
					</div>
				</div>
			</div>
		</div>

		<div class="row debug">
		</div>

		<footer class="footer">
			<div class="row">
				<div class="small-12 medium-6 large-8 columns" style="margin-top: 2.5rem;">
					<i><img src="images/logos/footer-gray.png" /></i>
					<p>
						<br />
						Copyright ⓒ2016 서성범. All rights reserved.<br />
					</p>
				</div>
				<div class="small-6 medium-3 large-2 columns">
				<h4>Links</h4>
					<ul class="footer-links">
						<li><a href="http://la-rp.co.kr">Forum</a></li>
						<li><a href="#">Information Website</a></li>
						<li><a href="./old">InfoWeb (OLD)</a></li>
						<li><a href="http://invite.teamspeak.com/la-rp.co.kr/?port=9988">Official Teamspeak</a></li>
						<li><a href="http://support.la-rp.co.kr/">Support Ticket</a></li>
					</ul>
				</div>
				<div class="small-6 medium-3 large-2 columns">
				<h4>Factions</h4>
					<ul class="footer-links">
						<li><a href="http://cafe.daum.net/LAPD-website">L.A.P.D</a></li>
						<li><a href="http://cafe.daum.net/lafdwebsite">L.A.F.D</a></li>
						<li><a href="http://cafe.daum.net/LADAEMYUNG">대명회</a></li>
					</ul>
				</div>
			</div>
		</footer>
	</div>

	<div class="blackscreen">
		<div></div>
	</div>

	<script src="js/vendor/jquery.js"></script>
	<script src="js/vendor/foundation.js"></script>
	<script>
	$(document).foundation();
	</script>
</body>

<script src="js/size.js"></script>
<script src="js/signin.js"></script>
<script src="js/playerinformation.js"></script>
<script src="js/serverstatus.js"></script>

</html>