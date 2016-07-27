<!doctype html>
<html class="no-js" lang="ko">

<head>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>LA:RP Information Website</title>
	<link rel="stylesheet" href="css/foundation.min.css">
	<link rel="stylesheet" href="css/app.css">
</head>

<body>
	<div class="top-bar-background"></div>
	<div class="top-bar">
		<div class="row">
			<div class="top-bar-title">
				<a href="index.html"><img src="images/logos/white_gray.png" /></a>
				<span class="menu-icon-container" data-responsive-toggle="responsive-menu" data-hide-for="medium">
					<button class="menu-icon" type="button" data-toggle></button>
				</span>
			</div>
			<div id="responsive-menu">
				<div class="top-bar-right">
					<ul class="menu" data-magellan data-bar-offset="30">
						<li><a href="#notice">Notice</a></li>
						<li class="show-signedin hide"><a href="#profile">Profile</a></li>
						<li><a href="#world">World</a></li>
						<li class="show-signedin"><a href="#admin">Admin</a></li>
						<li><a class="signin-button" href="#signin"></a></li>
					</ul>
				</div>
			</div>
		</div>
	</div>

	<div class="orbit topOrbit" role="region" data-orbit data-auto-play="false">
		<ul class="orbit-container">
			<button class="orbit-previous" aria-label="previous"><span class="show-for-sr">Previous Slide</span>&#9664;</button>
			<button class="orbit-next" aria-label="next"><span class="show-for-sr">Next Slide</span>&#9654;</button>
			<li class="orbit-slide is-active">
				<video poster="images/posters/shining_sun.jpg">
					<source src="videos/shining_sun.mp4" type="video/mp4" />
				</video>
			</li>
			<li class="orbit-slide">
				<video poster="images/posters/여자친구(GFRIEND) - 너 그리고 나 (NAVILLERA).jpg">
					<source src="videos/여자친구(GFRIEND) - 너 그리고 나 (NAVILLERA).mp4" type="video/mp4" />
				</video>
			</li>
			<li class="orbit-slide">
				<video poster="images/posters/Adam Levine - Lost Stars.jpg">
					<source src="videos/Adam Levine - Lost Stars.mp4" type="video/mp4" />
				</video>
			</li>
		</ul>
	</div>

	<div id="content-wrapper" class="topAnchor">
		<div id="notice" class="row content">
			<div class="small-12 medium-6 columns">
				<h1>Notice</h1>
				<div class="row">
					<div class="small-9 columns">
						<a>제목</a>
					</div>
					<div class="small-3 columns">16.07.20</div>
				</div>
				<div class="row">
					<div class="small-9 columns">
						<a>제목</a>
					</div>
					<div class="small-3 columns">16.07.20</div>
				</div>
				<div class="row">
					<div class="small-9 columns">
						<a>제목</a>
					</div>
					<div class="small-3 columns">16.07.20</div>
				</div>
			</div>
			<div class="small-12 medium-6 columns">
				<h1>Events</h1>
				<div class="row">
					<div class="small-9 columns">
						<a>제목</a>
					</div>
					<div class="small-3 columns">16.07.20</div>
				</div>
				<div class="row">
					<div class="small-9 columns">
						<a>제목</a>
					</div>
					<div class="small-3 columns">16.07.20</div>
				</div>
				<div class="row">
					<div class="small-9 columns">
						<a>제목</a>
					</div>
					<div class="small-3 columns">16.07.20</div>
				</div>
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
			<h1></h1>
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
									<button type="button" class="show-map button">상세 위치</button>
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
									<button type="button" class="show-map button">상세 위치</button>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="small-12 hide-for-large columns hide vmargin">
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
						<li><a href="#">Forum</a></li>
						<li><a href="#">Information Website</a></li>
						<li><a href="#">Official Teamspeak</a></li>
						<li><a href="#">Support Ticket</a></li>
					</ul>
				</div>
				<div class="small-6 medium-3 large-2 columns">
				<h4>Factions</h4>
					<ul class="footer-links">
						<li><a href="#">L.A.P.D</a></li>
						<li><a href="#">L.A.F.D</a></li>
						<li><a href="#">C.N.N</a></li>
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

<script>
var topbarOpacityFixed = false;
var topvideoMuted = true;
var topvideoVolume = 1;
var signedin = false;
var isMobile = {
	Android: function () {
		return navigator.userAgent.match(/Android/i);
	},
	BlackBerry: function () {
		return navigator.userAgent.match(/BlackBerry/i);
	},
	iOS: function () {
		return navigator.userAgent.match(/iPhone|iPad|iPod/i);
	},
	Opera: function () {
		return navigator.userAgent.match(/Opera Mini/i);
	},
	Windows: function () {
		return navigator.userAgent.match(/IEMobile/i);
	},
	Any: function () {
		return (isMobile.Android() || isMobile.BlackBerry() || isMobile.iOS() || isMobile.Opera() || isMobile.Windows());
	}
};

$(window).scroll(function() {
	setTopSectionDisplay();
});
$(window).resize(function() {
	if(topbarOpacityFixed && parseInt($('.top-bar').css('height')) == 100)
		topbarOpacityFixed = false;

	setOrbitHeight();
	setTopSectionDisplay();
});
$(document).ready(function () {
	checkSignedStatus();
	setInterval("checkSignedStatus()", 60000);
	setTopSectionDisplay();

	if(!isMobile.Any())
		$.each($('.orbit-slide.is-active video'), function() {
			this.muted = topvideoMuted;
			this.volume = topvideoVolume;
			this.play();
		});
	else {
		$('.topOrbit').css('position', 'static');
		$('#content-wrap').css('position', 'static');
	}

	$('[data-toggle]').on('click.zf.responsiveToggle', function() {
		if($('#responsive-menu').css('display') != 'none') {
			$('.top-bar-background').css('opacity', 1);
			topbarOpacityFixed = true;
		} else {
			topbarOpacityFixed = false;
			setTopSectionDisplay();
		}
	});

	$('.topOrbit').on('slidechange.zf.orbit', function(event) {
		var playingCnt = 0;

		$.each($('video'), function() {
			if(!this.paused) {
				this.pause();
				playingCnt++;
			}
		});
		if(playingCnt > 0)
			$.each($('.orbit-slide.is-active video'), function() {
				this.muted = topvideoMuted;
				this.volume = topvideoVolume;
				this.play();
			});

		setOrbitHeight();
		setTopSectionDisplay();
	});

	$('video').on('loadeddata', function() {
		setOrbitHeight();
		setTopSectionDisplay();
	});
	$('video').on('click', function() {
		if(this.paused)
			this.play();
		else
			this.pause();
	});
	$('video').on('volumechange', function() {
		topvideoMuted = this.muted;
		topvideoVolume = this.volume;
	});
	$('video').on('play', function() {
		setOrbitHeight();
		setTopSectionDisplay();
	});
	$('video').on('ended', function() {
		var slides = $('.orbit-slide');
			activeSlide = slides.filter('.is-active'),
			activeIndex = slides.index(activeSlide)+1,
			activeNum = activeIndex == slides.length ? 0 : activeIndex;
		
		$('.orbit-next').click();
		$.each($('.orbit-slide').eq(activeNum).children('video'), function() {
			this.muted = topvideoMuted;
			this.volume = topvideoVolume;
			this.play();
		});
	});

	$('.signin-submit').on('click', function() {
		if(!signedin) {
			var username = $('input.username').val(),
				password = $('input.password').val();

			$('input.username').val("");
			$('input.password').val("");

			$('.signin-submit').css('disabled', true);
			$('.blackscreen > div').html("로그인 처리중입니다.");
			$('.blackscreen').css('display', 'block');
			$('.blackscreen').fadeTo('slow', 1, function() {	
				$.ajax({
					type: "post",
					url: "functions/signin.php",
					cache: false,
					data: {
						cmd: 1,
						username: username,
						password: password
					}
				}).done(function(data) {
					data_splited = data.split('|');
					if(data_splited[0] == 0) {
						$('.signin-alert p').html(data_splited[1]);
						$('.signin-alert').css('display', 'block');
					}
					else if(data_splited[0] == 1) {
						signedin = true;
						setSignedDisplay();
					}
				}).always(function() {
					$(document).scrollTop($('#profile').offset().top-$('.top-bar').height());
					$('.blackscreen').fadeTo('slow', 0, function() {
						$('.blackscreen').css('display', 'none');
					});
				});
			});
		}
	});
	$('.signin-button').on('click', function() {
		if(signedin) {
			$('.signin-submit').prop('disabled', true);
			$('.blackscreen > div').html("로그아웃 처리중입니다.");
			$('.blackscreen').css('display', 'block');
			$('.blackscreen').fadeTo('slow', 1, function() {	
				$.ajax({
					type: "post",
					url: "functions/signin.php",
					cache: false,
					data: {
						cmd: 2
					}
				}).done(function(data) {
					if(data == 1) {
						signedin = false;
						setSignedDisplay();
					}
				}).always(function() {
					$(document).scrollTop($('#signin').offset().top-$('.top-bar').height());
					$('.blackscreen').fadeTo('slow', 0, function() {
						$('.signin-submit').prop('disabled', false);
						$('.blackscreen').css('display', 'none');
					});
				});
			});
		}
	});

	$('#profile .show-item-list').on('click', function() {
		showItemData(2);
	});

});

function checkSignedStatus() {
	$.ajax({
		type: "post",
		url: "functions/signin.php",
		cache: false,
		data: {
			cmd: 0
		}
	}).done(function(data) {
		if(signedin && data != 1) {
			$('.signin-alert p').html("닉네임 혹은 비밀번호가 변경되어 로그아웃되었습니다.");
			$('.signin-alert').css('display', 'block');
		}
		signedin = (data == 1) ? true : false;
		setSignedDisplay();
	});
}

function setSignedDisplay() {
	if(signedin) {
		loadPlayerInformation();
		$('.signin-button').html("Sign out");
		$('.show-signedin').removeClass('hide');
		$('.hide-signedin').addClass('hide');
	} else {
		$('.signin-button').html("Sign in");
		$('.show-signedin').addClass('hide');
		$('.hide-signedin').removeClass('hide');
	}
}

function setOrbitHeight() {
	var videoHeight = videoHeight = $('.orbit-slide.is-active video').height();

	if(parseInt(videoHeight) == 0)
		setTimeout("setOrbitHeight()", 10);
	else {
		$('.topOrbit').css('height', videoHeight);
		$('.topOrbit ul').css('height', videoHeight);
		$('.topOrbit ul li').css('height', videoHeight);
	}
}
function setTopSectionDisplay() {
	var anchorTop = $('.topOrbit').height();
	var scrollTop = $(document).scrollTop();
	var topbarOpacity = 1-(anchorTop-scrollTop)/anchorTop;

	if(!isMobile.Any()) {
		$('.topOrbit').css('top', -scrollTop/5);
		$('.topAnchor').css('top', anchorTop);
	}

	if(!topbarOpacityFixed)
		if(topbarOpacity >= 1)
			$('.top-bar-background').css('opacity', 1);
		else
			$('.top-bar-background').css('opacity', topbarOpacity);
}

function loadPlayerInformation() {
	$.ajax({
		type: "get",
		url: "functions/playerinfo.php",
		cache: false
	}).done(function(data) {
		data_splited = data.split('|');
		if(data_splited[0] == 0)
			checkSignedStatus();
		else if(data_splited[0] == 1) {
			var i = 1;
			var cnt;

			$('#profile .username').html(data_splited[i++]);
			$('#profile .badge.level').html(data_splited[i++]);
			if(data_splited[i++].length > 0)
				$('#profile .label.party').removeClass('hide').html(data_splited[i-1]);
			else
				$('#profile .label.party').addClass('hide');
			$('#profile .img img').attr('src', 'images/skins/' + data_splited[i++] + '.png');
			$('#profile .health').css('width', data_splited[i++] + '%');
			$('#profile .hunger').css('width', data_splited[i++] + '%');
			$('#profile .status-details').html(data_splited[i++]);

			var num_vehs = parseInt(data_splited[i++]);
			$('.each-vehicle > div').not($('.hide')).remove();
			for(cnt = 1; data_splited[i] == 'vehicle'; cnt++) {
				i++;
				var block = $('.each-vehicle div.vblock.hide').clone().appendTo('.each-vehicle').removeClass('hide');

				if(num_vehs == 1)
					block.addClass('large-centered').css('float', 'none');
				else if(cnt == num_vehs)
					block.addClass('end');
				else
					$('.each-vehicle div.vmargin.hide').clone().appendTo('.each-vehicle').removeClass('hide');

				block.find('.show-item-list').attr('vid', data_splited[i++]);
				block.find('.show-item-list').on('click', function() {
					showItemData(3, $(this).attr('vid'));
				});
				block.find('h3').html(data_splited[i++]);
				block.find('.img img')
					.attr('src', 'http://weedarr.wdfiles.com/local--files/veh/' + data_splited[i++] + '.png');
				block.find('.health').css('width', data_splited[i++] + '%');
				block.find('.fuel').css('width', data_splited[i++] + '%');
				block.find('.status-details').html(data_splited[i++]);
			}
		}
		else if(data_splited[0] == 2)
			setTimeout("loadPlayerInformation()", 1000);
	});
}

function showItemData(status, statusdata=null) {
	var cmd;

	if(status == 2)
		cmd = 0;
	else if(status == 3)
		cmd = 1;

	$.ajax({
		type: "post",
		url: "functions/item.php",
		cache: false,
		data: {
			cmd: cmd,
			vid: statusdata
		}
	}).done(function(data) {
		data_splited = data.split('|');
		if(data_splited[0] == 0)
			checkSignedStatus();
		else if(data_splited[0] == 1) {
			var i = 1;
			var cnt;
			var num_items = parseInt(data_splited[i++]);

			$('.item-data > tr').not($('.hide')).remove();
			for(cnt = 1; data_splited[i] == 'item'; cnt++) {
				i++;
				var block = $('.item-data tr.hide').clone().appendTo('.item-data').removeClass('hide');

				block.find('td:first-child').html(data_splited[i++]);
				block.find('td:nth-child(2)').html(data_splited[i++]);
				block.find('td:nth-child(3)').html(data_splited[i++]);
			}

			$("#items").foundation('open');
		}
	});
}
</script>

</html>