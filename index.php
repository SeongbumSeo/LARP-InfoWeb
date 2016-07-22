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
					<h1>Sign in with your <u>Nickname</u></h1>
				</div>
			</div>
			<div class="row">
				<div class="small-12 medium-9 large-7 medium-centered columns">
					<div class="input-group">
						<span class="input-group-label">Nickname</span>
						<input class="input-group-field id" type="text" />
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
							<h3></h3>
							<div class="success progress">
								<div class="progress-meter hunger"></div>
							</div>
							<div class="alert progress">
								<div class="progress-meter health"></div>
							</div>
							<div class="status-details-wrapper">
								<div class="status-details">
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
			var id = $('input.id').val(),
				password = $('input.password').val();

			$('input.id').val("");
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
						id: id,
						password: password
					}
				}).done(function(data) {
					data_splited = data.split('|');
					if(data_splited[0] == 0)
						alert(data_splited[1]);
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

	$.ajax({
		type: "post",
		url: "functions/signin.php",
		cache: false,
		data: {
			cmd: 0
		}
	}).done(function(data) {
		signedin = (data == 1) ? true : false;
		setSignedDisplay();
	});
});

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

function loadPlayerInformation() {
	$.ajax({
		type: "get",
		url: "functions/playerinfo.php",
		cache: false
	}).done(function(data) {
		data_splited = data.split('|');
		if(data_splited[0] == 0)
			alert(data_splited[1]);
		else if(data_splited[0] == 1) {
			$('#profile h3').html(data_splited[1]);
			$('#profile .img img').attr('src', 'images/skins/' + data_splited[2] + '.png');
			$('#profile .health').css('width', data_splited[3] + '%');
			$('#profile .hunger').css('width', data_splited[4] + '%');
			$('#profile .status-details').html(data_splited[5]);

			var max_vehs = parseInt(data_splited[6]);
			var cnt = 1;
			for(var i = 7; data_splited[i] != null; cnt++) {
				var block = $('.each-vehicle div.vblock.hide').clone().appendTo('.each-vehicle').removeClass('hide');

				if(max_vehs == 1)
					block.addClass('large-centered').css('float', 'none');
				else if(cnt == max_vehs)
					block.addClass('end');
				else
					$('.each-vehicle div.vmargin.hide').clone().appendTo('.each-vehicle').removeClass('hide');

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
</script>

</html>