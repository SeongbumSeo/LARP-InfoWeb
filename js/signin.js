var signedin = false;

$(document).ready(function () {
	updateSignedStatus();

	$('.signin-submit').on('click', function() { signIn() });
	$('#signin .input-group-field').on('keypress', function(e) {
		if(e.keyCode == 13)
			signIn();
	});
	$('.signin-button').on('click', function() { signOut() });
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
	}).always(function() {
		setTimeout("updateSignedStatus()", 30000);
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

function signIn() {
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
			}).fail(function() {
				$('.signin-alert p').html("시스템 오류가 발생하여 로그인에 실패하였습니다.");
				$('.signin-alert').css('display', 'block');
			}).always(function() {
				$(document).scrollTop($('#profile').offset().top-$('.top-bar').height());
				$('.blackscreen').fadeTo('slow', 0, function() {
					$('.blackscreen').css('display', 'none');
				});
			});
		});
	}
}

function signOut() {
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
					$('.signin-submit').prop('disabled', false);
					$(document).scrollTop($('#signin').offset().top-$('.top-bar').height());
					setSignedDisplay();
				}
			}).fail(function() {
				alert("시스템 오류가 발생하여 로그아웃에 실패하였습니다.");
			}).always(function() {
				$('.blackscreen').fadeTo('slow', 0, function() {
					$('.blackscreen').css('display', 'none');
				});
			});
		});
	}
}