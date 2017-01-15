var topbarOpacityFixed = false;
var topposterMuted = true;
var topposterVolume = 1;
var signedin = false;

var gamePlayers = 0;
var gameMode = null;
var playerList = null;
var playerIdArr = new Array();
var playerNameArr = new Array();
var playerPingArr = new Array();

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
	updateSignedStatus();
	updateServerStatus();
	setInterval("updateSignedStatus()", 60000);
	setInterval("updateServerStatus()", 2500);
	setTopSectionDisplay();

	if(!isMobile.Any())
		$.each($('.orbit-slide.is-active video'), function() {
			this.muted = topposterMuted;
			this.volume = topposterVolume;
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

		$.each($('.poster'), function() {
			if(!this.paused) {
				this.pause();
				playingCnt++;
			}
		});
		if(playingCnt > 0)
			$.each($('.orbit-slide.is-active .poster'), function() {
				this.muted = topposterMuted;
				this.volume = topposterVolume;
				this.play();
			});

		setOrbitHeight();
		setTopSectionDisplay();
	});

	$('.poster').on('loadeddata', function() {
		setOrbitHeight();
		setTopSectionDisplay();
	});
	$('.poster').on('click', function() {
		if(this.paused)
			this.play();
		else
			this.pause();
	});
	$('.poster').on('volumechange', function() {
		topposterMuted = this.muted;
		topposterVolume = this.volume;
	});
	$('.poster').on('play', function() {
		setOrbitHeight();
		setTopSectionDisplay();
	});
	$('.poster').on('ended', function() {
		var slides = $('.orbit-slide');
			activeSlide = slides.filter('.is-active'),
			activeIndex = slides.index(activeSlide)+1,
			activeNum = activeIndex == slides.length ? 0 : activeIndex;
		
		$('.orbit-next').click();
		$.each($('.orbit-slide').eq(activeNum).children('.poster'), function() {
			this.muted = topposterMuted;
			this.volume = topposterVolume;
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
		showItemData("내 아이템", 2);
	});

});

function updateSignedStatus() {
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

function updateServerStatus() {
	$.ajax({
		type: "get",
		url: "functions/serverstatus.php",
		cache: false
	}).done(function(data) {
		var statusdata = data.split('|');
		if(statusdata.length != 2) {
			gamePlayers = statusdata[0];
			gameMode = statusdata[1];
			playerList = "<tr><th>ID</th><th>닉네임</th><th>핑</th></tr>";
			for(var i = 2; i < statusdata.length; i++) {
				var playerdata = statusdata[i].split(',');
				playerIdArr[i] = playerdata[0];
				playerNameArr[i] = playerdata[1];
				playerPingArr[i] = playerdata[2];
				playerList += "<tr><td>" + playerIdArr[i] + "</td><td>" + playerNameArr[i] + "</td><td>" + playerPingArr[i] + "</td></tr>";
			}
		}
	});

	if(gamePlayers == "closed") {
		$('.serverstatus-loading').addClass('hide');
		$('.serverstatus-offline').removeClass('hide');
		$('.serverstatus-online').addClass('hide');
	} else if(!gameMode) {
		$('.serverstatus-loading').removeClass('hide');
		$('.serverstatus-offline').addClass('hide');
		$('.serverstatus-online').addClass('hide');
	} else {
		$("td.gameplayers").html(gamePlayers);
		$("td.gamemode").html(gameMode);
		$("table.playerlist").html(playerList);

		$('.serverstatus-loading').addClass('hide');
		$('.serverstatus-offline').addClass('hide');
		$('.serverstatus-online').removeClass('hide');
	}
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
	var posterHeight = posterHeight = $('.orbit-slide.is-active .poster').height();

	if(parseInt(posterHeight) == 0)
		setTimeout("setOrbitHeight()", 10);
	else {
		$('.topOrbit').css('height', posterHeight);
		$('.topOrbit ul').css('height', posterHeight);
		$('.topOrbit ul li').css('height', posterHeight);
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
			updateSignedStatus();
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
				block.find('.show-item-list').attr('vname', data_splited[i++]);
				block.find('.show-item-list').on('click', function() {
					showItemData($(this).attr('vname'), 3, $(this).attr('vid'));
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

function showItemData(caption, status, statusdata=null) {
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
			updateSignedStatus();
		else if(data_splited[0] == 1) {
			var i = 1;
			var cnt;
			var num_items = parseInt(data_splited[i++]);

			$('#items > h3').html(caption);

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