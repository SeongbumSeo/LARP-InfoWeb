<?php
include("config.php");
?>
<!doctype html>
<html class="no-js" lang="ko">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
	<title>LA:RP Information Website</title>
	<link rel="stylesheet" href="css/foundation.css">
	<link rel="stylesheet" href="css/app.css?time=<?=time()?>">
</head>

<body>
	<div class="top-bar-background"></div>
	<div class="top-bar">
		<div class="row">
			<div class="top-bar-title">
				<a href="."><img src="images/logos/white_gray.png" /></a>
				<span class="menu-icon-container" data-responsive-toggle="responsive-menu" data-hide-for="medium">
					<button class="menu-icon" type="button" data-toggle></button>
				</span>
			</div>
			<div id="responsive-menu">
				<div class="top-bar-right">
					<ul class="menu" data-magellan data-bar-offset="30">
						<li><a href="#notice">공지사항</a></li>
						<li class="show-signedin"><a href="#profile">프로필</a></li>
						<li class="show-signedin"><a href="#world">월드</a></li>
						<li class="show-signedin"><a href="./old/admin.php">관리</a></li>
						<li><a class="signin-button" href="#signin"><!--js/signin.js--></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="orbit topOrbit" role="region" data-orbit data-auto-play="false">
		<ul class="orbit-container">
			<div class="capital">
				<h1>로스앤젤레스 역할 연기</h1>
				<hr />
				<h2>Realization of Imagination</h2>
			</div>
			<li class="orbit-slide is-active">
				<img class="poster" src="images/posters/hoil-ryu-38187.jpg" />
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
				<h1>공지사항</h1>
				<table>
					<tbody class="notice-list">
						<tr class="hide">
							<td><!--js/notice.js--></td>
							<td><!--js/notice.js--></td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="small-12 medium-6 columns">
				<h1>업데이트</h1>
				<table>
					<tbody class="update-list">
						<tr class="hide">
							<td><!--js/notice.js--></td>
							<td><!--js/notice.js--></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>

		<hr />

		<div id="signin" class="content hide-signedin hide">
			<div class="row">
				<div class="column">
					<h1><center>로그인</center></h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 large-7 medium-centered columns" style="text-align: center;">
					<div class="signin-alert alert callout" data-closable="slide-out-right" style="display: none;">
						<p><!--js/signin.js--></p>
						<button class="close-button" aria-label="Dismiss alert" type="button" data-close>
							<span aria-hidden="true">&times;</span>
						</button>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 large-7 medium-centered columns">
					<div class="input-group">
						<span class="input-group-label">닉네임</span>
						<input class="input-group-field username" type="text" />
					</div>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 large-7 medium-centered columns">
					<div class="input-group">
						<span class="input-group-label">비밀번호</span>
						<input class="input-group-field password" type="password" />
						<div class="input-group-button">
							<input type="submit" class="button signin-submit" value="로그인" />
						</div>
					</div>
				</div>
			</div>
		</div>

		<div id="items" class="reveal show-signedin hide" data-reveal>
			<h3><!--js/playerinformation.js--></h3>
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
						<td><!--js/playerinformation.js--></td>
						<td><!--js/playerinformation.js--></td>
						<td><!--js/playerinformation.js--></td>
					</tr>
				</tbody>
			</table>
			<button class="close-button" data-close type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div id="map" class="reveal show-signedin hide" data-reveal>
			<h3><!--js/map.js--></h3>
			<div id="map-canvas">
			</div>
			<button class="goback-button" type="button">
				<span aria-hidden="true">&lt;</span>
			</button>
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
						<th>내용</th>
						<th>날짜</th>
					</tr>
				</thead>
				<tbody class="usagelog-data">
					<tr class="hide">
						<td><!--js/playerinformation.js--></td>
						<td><!--js/playerinformation.js--></td>
						<td><!--js/playerinformation.js--></td>
					</tr>
				</tbody>
			</table>
			<button class="close-button" data-close type="button">
				<span aria-hidden="true">&times;</span>
			</button>
		</div>

		<div id="carblowlog" class="reveal show-signedin hide" data-reveal>
			<h3><!--js/playerinformation.js--></h3>
			<table>
				<thead>
					<tr>
						<th>사고자</th>
						<th>위치</th>
						<th>날짜</th>
					</tr>
				</thead>
				<tbody class="carblowlog-data">
					<tr class="hide">
						<td><!--js/playerinformation.js--></td>
						<td><!--js/playerinformation.js--></td>
						<td><!--js/playerinformation.js--></td>
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
					<h1>프로필</h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 medium-centered columns">
					<div class="row">
						<div class="small-12 large-4 columns img">
							<img /><!--js/playerinformation.js-->
						</div>

						<div class="small-12 large-8 columns">
							<span class="secondary label party hide top" data-tooltip title="파티"><!--js/playerinformation.js--></span>
							<h3>
								<span class="username left" data-tooltip title="닉네임"><!--js/playerinformation.js--></span>
								<span class="badge level right" data-tooltip title="레벨"><!--js/playerinformation.js--></span>
							</h3>
							<div class="success progress top">
								<div class="progress-meter hunger"><!--js/playerinformation.js--></div>
							</div>
							<div class="alert progress">
								<div class="progress-meter health"><!--js/playerinformation.js--></div>
							</div>
							<div class="status-details-wrapper">
								<div class="status-details">
									<!--js/playerinformation.js-->
								</div>
								<div class="expanded button-group">
									<button type="button" class="show-item-list button">
										<img src="images/icons/backpack.png" />
										<span>아이템</span>
									</button>
									<button type="button" class="show-map button">
										<img src="images/icons/map.png" />
										<span>위치</span>
									</button>
									<button type="button" class="show-usagelog button">
										<img src="images/icons/calendar.png" />
										<span>이용 로그</span>
									</button>
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
					<h1>차량</h1>
				</div>
			</div>
			<div class="row each-vehicle">
				<div class="small-12 large-6 columns hide vblock">
					<div class="row">
						<div class="small-12 medium-4 columns img">
							<img /><!--js/playerinformation.js-->
						</div>

						<div class="small-12 medium-8 columns">
							<h3><!--js/playerinformation.js--></h3>
							<div class="progress">
								<div class="progress-meter fuel"><!--js/playerinformation.js--></div>
							</div>
							<div class="alert progress">
								<div class="progress-meter health"><!--js/playerinformation.js--></div>
							</div>
							<div class="status-details-wrapper">
								<div class="status-details">
									<!--js/playerinformation.js-->
								</div>
								<div class="expanded button-group">
									<button type="button" class="show-item-list button">
										<img src="images/icons/backpack.png" />
										<span>아이템</span>
									</button>
									<button type="button" class="show-map button">
										<img src="images/icons/map.png" />
										<span>위치</span>
									</button>
									<button type="button" class="show-carblowlog button">
										<img src="images/icons/explosion.png" />
										<span>블로우 로그</span>
									</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<hr class="show-signedin" />

		<div id="world" class="content show-signedin hide">
			<div class="row">
				<div class="column">
					<h1>월드</h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-7 large-5 medium-centered columns">
					<div class="serverstatus-loading hide">로딩중</div>
					<div class="serverstatus-error hide">월드 데이터를 불러오지 못하였습니다.</div>
					<div class="serverstatus-offline hide">게임 서버 점검중</div>
					<div class="serverstatus-online hide">
						<table class="serverinfo">
							<tr>
								<th>주소</th>
								<td class="gameaddress"><!--js/serverstatus.js--></td>
							</tr>
							<tr>
								<th>인원</th>
								<td class="gameplayers"><!--js/serverstatus.js--></td>
							</tr>
							<tr>
								<th>버전</th>
								<td class="gamemode"><!--js/serverstatus.js--></td>
							</tr>
						</table>
						<table class="playerlist">
							<thead>
								<tr>
									<th>ID</th>
									<th>닉네임</th>
									<th>핑</th>
								</tr>
							</thead>
							<tbody>
								<!--js/serverstatus.js-->
							</tbody>
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
						<li><a href="http://la-rp.co.kr">LA:RP</a></li>
						<li><a href="./old">인포웹 구버전</a></li>
						<li><a href="http://invite.teamspeak.com/la-rp.co.kr/?port=9988">공식 팀스피크</a></li>
						<li><a href="http://support.la-rp.co.kr/">서포트 티켓</a></li>
					</ul>
				</div>
				<div class="small-6 medium-3 large-2 columns">
					<h4>Factions</h4>
					<ul class="footer-links">
						<li><a href="http://cafe.daum.net/LAPD-website">L.A.P.D</a></li>
						<li><a href="http://cafe.daum.net/lafdwebsite">L.A.F.D</a></li>
						<li><a href="http://cafe.daum.net/LADAEMYUNG">대명회</a></li>
						<li><a href="http://cafe.daum.net/Coosana">C.C.G</a></li>
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
	<script src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_API_KEY?>" defer></script>
	<script src="js/SanMap/SanMap.min.js" defer></script>
	<script>
	$(document).foundation();
	</script>
</body>

<script src="js/size.js?time=<?=time()?>"></script>
<script src="js/notice.js?time<?=time()?>"></script>
<script src="js/signin.js?time=<?=time()?>"></script>
<script src="js/playerinformation.js?time=<?=time()?>"></script>
<script src="js/serverstatus.js?time=<?=time()?>"></script>
<script src="js/map.js?time=<?=time()?>"></script>

</html>