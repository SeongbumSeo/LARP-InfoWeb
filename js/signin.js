var signedin = false;

$(document).ready(function () {
	updateSignedStatus();
	setInterval("updateSignedStatus()", 10000);

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
					switch(parseInt(data)) {
						case 0:
							var data_splited = data.split('|');
							$('.signin-alert p').html(data_splited[1]);
							$('.signin-alert').css('display', 'block');
							break;
						case 1:
							signedin = true;
							setSignedDisplay();
							break;
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
					if(parseInt(data) == 1) {
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
		if(signedin && parseInt(data) != 1) {
			$('.signin-alert p').html("닉네임 혹은 비밀번호가 변경되어 로그아웃되었습니다.");
			$('.signin-alert').css('display', 'block');
		}
		signedin = (parseInt(data) == 1) ? true : false;
		setSignedDisplay();
	});
}

function setSignedDisplay() {
	if(signedin) {
		loadPlayerInformation();
		$('.signin-button').html("로그아웃");
		$('.show-signedin').removeClass('hide');
		$('.hide-signedin').addClass('hide');
	} else {
		$('.signin-button').html("로그인");
		$('.show-signedin').addClass('hide');
		$('.hide-signedin').removeClass('hide');
	}
}