var topbarOpacityFixed = false;
var topposterMuted = true;
var topposterVolume = 1;
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

$(document).ready(function() {
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
});

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