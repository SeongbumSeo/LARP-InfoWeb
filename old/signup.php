<?php
require("initial.inc.php");
require("signup_functions.inc.php");

UnsetSignUpSessions();

$query_sa = GetSignedAccountsByIP($_SERVER['REMOTE_ADDR'],$DB);
$numsa = $query_sa->num_rows;
$maapplied = false;
if($numsa > 0) {
	$query_ma = $DB->query("SELECT Username FROM multiaccount_data WHERE IP = '$_SERVER[REMOTE_ADDR]'");
	$maapplied = ($query_ma->num_rows > 0);
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
require("head.inc.php");
?>
		<title>Sign Up - LA:RP Information Website</title>
		<script type="text/javascript">
			var temp_username = null;
			var temp_password = null;
			var temp_password_reinput = null;
			var auth_count = 0;
			var auth_timer = null;
			
			$(document).ready(function() {
				$(".navl_signup").addClass("active");
				
<?php
if($numsa < 1 || $maapplied) {
?>
				setInterval(function() {
					var username = $('#signup_inp_username').val();
					var password = $('#signup_inp_password').val();
					var password_reinput = $('#signup_inp_password_reinput').val();
					var color_warn_password = "#AA0000";
					var color_warn_password_reinput = "#AA0000";
					
					if(temp_username != username) {
						temp_username = username;
						$.post('signup_check_username.php', {
							name: username
						},function(data) {
							$('.signup_warn_username').html(data);
						});
					}
					
					if(temp_password != password || temp_password_reinput != password_reinput) {
						temp_password = password;
						temp_password_reinput = password_reinput;
						
						if(password.length < 1)
							$('#signup_label_password').css('display','none');
						if(password_reinput.length < 1)
							$('#signup_label_password_reinput').css('display','none');
						
						if(password.length >= 4)
							color_warn_password = "#00AA00";
						$('.signup_warn_password').html("<p style=\"color: " + color_warn_password + ";\">1. 비밀번호가 4자리 이상이어야 합니다.</p>");
						
						if(password == password_reinput)
							color_warn_password_reinput = "#00AA00";
						$('.signup_warn_password_reinput').html("<p style=\"color: " + color_warn_password_reinput + ";\">1. 비밀번호가 일치해야 합니다.</p>");
					}
				},100);
				
				$('#signup_inp_password,#signup_inp_password_reinput').on('keypress', function(event) {
					var keyCode = event.keyCode;
					var shiftKey = event.shiftKey;
					if(((keyCode >= 65 && keyCode <= 90) && !shiftKey)
			  		|| ((keyCode >= 97 && keyCode <= 122) && shiftKey)) {
						var warnObj = null;
						if($(this).attr('id') == 'signup_inp_password')
							warnObj = $('#signup_label_password');
						else
							warnObj = $('#signup_label_password_reinput');
						warnObj.css('display','block');
					}
				});
				
				$('#mailsend_sub').click(function() {
					var username = $('#signup_inp_username').val();
					var password = $('#signup_inp_password').val();
					var password_reinput = $('#signup_inp_password_reinput').val();
					var email = $('#mailsend_inp_email').val();
					
					if(email.length < 1)
						alert("휴대전화 번호를 입력하세요.");
					else
						$.post('signup_mailsend.php', {
							name: username,
							password: password,
							password_reinput: password_reinput,
							email: email
						},function(data) {
							var splited = data.split('|');
							if(parseInt(splited[0]) == 1) {
								$('.daauth').attr('disabled',true);
								$('.daauth').css('background-color','#DDD');
								$('.auth_form').css('display','table');
								$('#auth_inp_authcode').val('');
								$('#auth_inp_authcode').focus();
								auth_count = <?=AUTH_TIME?>+1;
								auth_timer = setInterval(function() {
									auth_count--;
									$('#auth_count').html(auth_count);
									if(auth_count < 1) {
										$('.daauth').attr('disabled',false);
										$('.daauth').css('background-color','#FFF');
										$('.auth_form').css('display','none');
										$('#auth_inp_authcode').val('');
										$('#mailsend_inp_mailemail').focus();
										clearInterval(auth_timer);
										alert("인증번호가 만료되었습니다.\n이메일 인증을 다시 시도해 주세요.");
									}
								},1000);
							}
							alert(splited[1]);
						});
					return false;
				});
				
				$('#auth_sub').click(function() {
					var username = $('#signup_inp_username').val();
					var password = $('#signup_inp_password').val();
					var password_reinput = $('#signup_inp_password_reinput').val();
					var authcode = $('#auth_inp_authcode').val();
					
					if(authcode.length < 1)
						alert("인증번호를 입력하세요.");
					else
						$.post('signup_submit.php', {
							name: username,
							password: password,
							password_reinput: password_reinput,
							authcode: authcode
						},function(data) {
							var splited = data.split('|');
							var funccode = parseInt(splited[0]);
							if(funccode == 0) {
								$('.daauth').attr('disabled',false);
								$('.daauth').css('background-color','#FFF');
								$('.auth_form').css('display','none');
								clearInterval(auth_timer);
							}
							alert(splited[1]);
							if(funccode == 1) {
								clearInterval(auth_timer);
								$(location).attr('href','./login.php');
							}
							if(funccode == 2)
								$('#auth_inp_authcode').focus();
						});
				});
<?php
}
?>
			});
		</script>
	</head>

	<body>
<?php
require("navbar.inc.php");
?>
		<div id="contents">
<?php
require("header.inc.php");

if($numsa > 0 && !$maapplied) {
?>
			<div id="conbox" class="head" style="background-color: #555;">
				<h1>다중계정 가입 방지 안내</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #333; color: #FFF; text-align: center; font-size: 0.9em;">
				<p style="font-size: 1.0em; color: #FF9900;">회원님의 IP에서 아래의 계정이 가입되어 있습니다!</p>
				<p>&nbsp;</p>
<?php
	foreach(mysql_fetch_row($query_sa) as $name)
		print("<p>$name</p>");
?>
			</div>
<?php
} else {
?>
			<div id="conbox" class="head" style="background-color: #555;">
				<h1>튜토리얼</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #333; color: #FFF; font-size: 0.9em;">
				<p style="font-size: 1.0em; text-align: center; color: #FF9900;">[ Los Angeles Role Play 소개 ]</p>
				<p>&nbsp;</p>
				<p><span style="color: #6DE5D0">Los Angeles Role Play(이하 'LA:RP')</span>는 <span style="color: #FAF5A4">2009년 01월 12일</span>부터 이어져 온 국내 장수 Role Playing 서버입니다.</p>
				<p>저희 운영진들은 유저분들께 높은 만족감과 즐거움을 드리기 위하여 항상 최선의 노력을 다 하고 있습니다.</p>
				<p>LA:RP는 100만 회원과 함께하는 국내 1위 <span style="color: #FFB2BE;">다음 GTA자료실 카페의 공식 Role Playing 서버</span>입니다.</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p style="font-size: 1.0em; text-align: center; color: #FF9900;">[ Los Angeles Role Play 슬로건 ]</p>
				<p>&nbsp;</p>
				<p>Los Angeles Role Play의 슬로건은 <span style="color: #5DCEF0;">'the Realization of Imagination'</span>입니다.</p>
				<p>여러분들의 <span style="color: #FAF5A4;">상상을 실현</span>시킬 수 있는 발판을 제공하기 위하여 항상 노력하겠습니다.</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p style="font-size: 1.0em; text-align: center; color: #FF9900;">[ Role Playing - 역할 연기란? ]</p>
				<p>&nbsp;</p>
				<p><span style="color: #6DE5D0;">Role Playing(이하 'RP')</span>이란, <span style="color: #5DCEF0;">역할 연기</span>라는 의미로써, '캐릭터에 알맞는 역할을 현실적으로 연기하는 것'을 말합니다.</p>
				<p>LA:RP에서는 이 게임 방식을 채택하고 있으며 따라서 사용자는 RP의 게임 규율을 따라야 합니다.</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p style="font-size: 1.0em; text-align: center; color: #FF9900;">[ Role Playing의 구체적 설명 ]</p>
				<p>&nbsp;</p>
				<p>영화 '아바타'를 보신 적이 있으시다면 금방 이해를 하실 수도 있습니다.</p>
				<p>&nbsp;</p>
				<p>영화 '아바타'에서 주인공인 제이크 설리가 아바타 프로그램에 접속해 판도라 행성의 생명체 아바타를 제어합니다.</p>
				<p>RP에서 <span style="color: #BA94DD;">판도라 행성은 게임 속 세상</span>, <span style="color: #5DCEF0;">아바타는 게임 속 캐릭터</span>입니다.</p>
				<p>아바타(게임 속 캐릭터)가 판도라 행성(게임 속 세상 - IC)에서 현실(게임 밖 세상 - OOC)에서와 같은 오감을 느끼며 행동하죠.</p>
				<p>&nbsp;</p>
				<p>단, RP에서 <span style="color: #FFB2BE;">오감은 직접 표현</span>해야 합니다.</p>
				<p>게임 특성상 오감을 자동적으로 표현하게 할 수는 없기 때문입니다.</p>
				<p>따라서 오감을 표현하기 위한 다양한 명령어들이 존재합니다. <span style="color: #FAF5A4;">('/do' - 상태, '/me' - 행동, '/so' - 소리)</span></p>
				<p>이 명령어들을 사용해 오감을 표현할 수 있습니다.</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p>&nbsp;</p>
				<p style="font-size: 1.0em; text-align: center; color: #FF9900;">[ Role Playing - 용어 설명 ]</p>
				<p>&nbsp;</p>
				<p><span style="color: #5DCEF0;">RP</span></p>
				<p>Role Play의 약자로 역할연기를 뜻합니다.</p>
				<p>게임 속에서 현실처럼 직업과 상황 등에 맞게 행동해야 합니다.</p>
				<p>&nbsp;</p>
				<p><span style="color: #5DCEF0;">IC</span></p>
				<p>In Character의 약자로 게임 속 캐릭터가 존재하는 세상입니다.</p>
				<p>게임속에서 현실의 상황이나 대화를 나눌 경우 OOC In IC로써 규칙 위반입니다.</p>
				<p>&nbsp;</p>
				<p><span style="color: #5DCEF0;">OOC</span></p>
				<p>Out of Character의 약자로 컴퓨터 앞의 플레이어, 즉 유저가 존재하는 세계입니다.</p>
				<p>OOC 대화로 게임 속의 내용을 주고받을 경우 IC In OOC로써 규칙 위반입니다.</p>
				<p>&nbsp;</p>
				<p><span style="color: #5DCEF0;">Meta Gaming(메타 게이밍)</span></p>
				<p>'ㅋㅋ,ㄴㄴ'같은 현실에서 발음이 불가능한 초성어나 인터넷 용어를 IC에서 사용하는 경우입니다.</p>
				<p>RP는 역할연기입니다. 현실과 같이 행동해야 하며 당연히 초성어를 발음하지 못하므로 규칙 위반입니다.</p>
				<p>&nbsp;</p>
				<p><span style="color: #5DCEF0;">Power Gaming(파워 게이밍)</span></p>
				<p>현실에서 불가능한 행동을 /me 또는 /do로 사용하는 경우입니다.</p>
				<p>'/me 자동차를 들어 올린다.' '/do 총알에 맞은 곳이 저절로 아물었다.' 등의 사례가 있습니다.</p>
				<p>&nbsp;</p>
				<p><span style="color: #5DCEF0;">Bunny Hopping(버니 합핑)</span></p>
				<p>Bunny(토끼)와 Hopping(돌아다니다)의 합성어입니다. 점프하며 이동하는 것을 뜻합니다.</p>
				<p>현실에서는 이러한 모습으로 움직이지 않으므로 규칙 위반이며 금지 사항입니다.</p>
			</div>

<?php
	if($maapplied) {
?>
			<div id="conbox" class="head" style="background-color: #555;">
				<h1>다중계정 가입 안내</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #333; color: #FFF; text-align: center; font-size: 0.9em;">
				<p style="font-size: 1.0em; color: #FF9900;">회원님은 다중계정 가입 승인자로서 아래의 닉네임으로 가입할 수 있습니다.</p>
				<p>&nbsp;</p>
<?php
		foreach(mysql_fetch_row($query_ma) as $name)
			printf("<p>%s</p>",$name);
?>
			</div>
<?php
	}
?>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>계정 정보 입력</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<table class="signup_form">
					<tr class="signup_username">
						<th>닉네임</th>
						<td><input id="signup_inp_username" class="daauth" type="text" /></td>
					</tr>
					<tr>
						<td></td>
						<td class="signup_warn_username"></td>
					</tr>
					
					<tr class="signup_password">
						<th>비밀번호</th>
						<td>
							<label for="signup_inp_password" id="signup_label_password" class="normallbl signup_label">Caps Lock</label>
							<input id="signup_inp_password" class="daauth" type="password" />
						</td>
					</tr>
					<tr >
						<td></td>
						<td class="signup_warn_password"></td>
					</tr>
					
					<tr class="signup_password_reinput">
						<th>비밀번호 확인</th>
						<td>
							<label for="signup_inp_password" id="signup_label_password_reinput" class="normallbl signup_label">Caps Lock</label>
							<input id="signup_inp_password_reinput" class="daauth" type="password" />
						</td>
					</tr>
					<tr >
						<td></td>
						<td class="signup_warn_password_reinput"></td>
					</tr>
				</table>
			</div>
					
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>이메일 인증</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<table class="simpleform">
					<tr>
						<td>
							<label for="mailsend_inp_email" class="daauth mailsend_lbl normallbl">이메일 주소</label>
							<input id="mailsend_inp_email" class="daauth mailsend_inp" name="mailsend_email" type="text" />
						</td>
						<td>
							<button id="mailsend_sub" class="daauth">인증번호 발송</button>
						</td>
					</tr>
				</table>
				<table class="auth_form">
					<tr>
						<td>&nbsp;</td>
						<td>
							<input id="auth_inp_authcode" />
						</td>
						<td>
							<button id="auth_sub">인증 및 회원가입</button>
						</td>
						<td id="auth_count">&nbsp;</td>
					</tr>
				</table>
			</div>
<?php
}
require("footer.inc.php");
?>
		</div>
	</body>
</html>
<?php
mysql_close();
?>