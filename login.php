<?php
include "./initial.inc.php";

if(isset($_POST['username']) && isset($_POST['password'])) {
	$dbUsername = mysql_real_escape_string(str_replace(' ','_',$_POST['username']));
	$dbPassword = mysql_real_escape_string($_POST['password']);
	$loginquery = sprintf("SELECT Username FROM user_data WHERE (Username = '%s' OR (AdminName = '%s' AND Admin > 0)) AND Password = SHA1('%s') LIMIT 1",
		$dbUsername,mysql_real_escape_string($_POST['username']),$dbPassword);
	$loginresult = mysql_query($loginquery,$DB);
	if(mysql_num_rows($loginresult) > 0) {
		$password = mysql_query("SELECT SHA1('".$dbPassword."')",$DB);
		$_SESSION['username'] = mysql_result($loginresult,0);
		$_SESSION['password'] = mysql_result($password,0);
		header("location: ".HTTP_HOST);
	}
	else {
		$_SESSION['logintry']++;
		if($_SESSION['logintry'] > MAX_LOGINTRY) {
			$_SESSION['logintry'] = 0;
			mysql_query(sprintf(
				"INSERT INTO infoweb_ban_data (IP,Reason,Until) VALUES ('%s','로그인 %d회 실패',DATE_ADD(NOW(),INTERVAL %d HOUR))",
				$_SERVER['REMOTE_ADDR'],MAX_LOGINTRY+1,BAN_TIME),$DB);
		}
		header("location: ".HTTP_SELF);
	}
	exit;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
?>
		<title>Log In - LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".navl_login").addClass("active");
				$("#login_inp_id").focus();
				
				$('.login_sub').click(function() {
					if($('#login_inp_id').val().length < 1 || $('#login_inp_pw').val().length < 1) {
						alert("ID와 비밀번호를 모두 입력하세요.");
						return false;
					}
					else if($(this).attr('class') == "signup_sub") {
						$('.smsauth').each(function() {
							$(this).css('display','block');
						});
						return false;
					}
					this.form.submit();
				});
				
				$('.signup_sub').click(function() {
					$(location).attr('href',"./signup.php");
					return false;
				});
			});
		</script>
	</head>

	<body>
<?php
include "./navbar.inc.php";
?>
		<div id="contents">
<?php
include "./header.inc.php";
if($_SESSION['logintry'] > 0) {
?>
			<div id="conbox" style="background-color: #FFF; color: #FF0000;">
				<p>아이디 혹은 비밀번호를 확인해 주십시오. (<?=$_SESSION['logintry']?>/<?=MAX_LOGINTRY?>)</p>
			</div>
<?php
}
?>
			<div id="conbox" class="head" style="background-color: #AADDAA;">
				<h1>환영합니다!</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #F0FFF0;">
				<form name="loginfrm" action="login.php" method="post">
					<table class="contable logintable">
						<tr>
							<td>
								<label for="login_inp_id" class="login_lbl">인게임 닉네임</label>
								<input id="login_inp_id" class="login_inp" name="username" type="text" tabindex="1" />
							</td>
							<td rowspan="2">
								<button class="login_sub" tabindex="3">로그인</button>
							</td>
							<!--<td rowspan="2">
								<button class="signup_sub" tabindex="4">회원가입</button>
							</td>-->
						</tr>
						<tr>
							<td>
								<label for="login_inp_pw" class="login_lbl">비밀번호</label>
								<input id="login_inp_pw" class="login_inp" name="password" type="password" tabindex="2" />
							</td>
						</tr>
					</table>
				<form>
			</div>
<?php
include "./footer.inc.php";
?>
		</div>
	</body>
</html>
<?php
mysql_close();
?>