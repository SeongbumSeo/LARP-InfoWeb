<?php
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
header('Cache-Control: no-cache, must-revalidate'); 
header('Pragma: no-cache');
header('Content-Type: text/html');

session_start();

require("defines.inc.php");
require("signup_functions.inc.php");

if(!isset($_POST['name'])
|| !isset($_POST['password'])
|| !isset($_POST['password_reinput'])
|| !isset($_POST['authcode'])
|| !isset($_SESSION['email'])
|| !isset($_SESSION['auth_code'])
|| !isset($_SESSION['auth_time'])) {
	print("2|데이터를 전송받지 못했습니다.");
	exit;
}

if($_SESSION['auth_time']+AUTH_TIME < time()) {
	UnsetSignUpSessions();
	print("0|인증번호가 만료되었습니다.\n이메일 인증을 다시 시도해 주세요.");
	exit;
}

$input_authcode = $_POST['authcode'];
$authcode = $_SESSION['auth_code'];
if(strcmp($input_authcode,$authcode) != 0) {
	$_SESSION['auth_try']++;
	if($_SESSION['auth_try'] >= MAX_AUTH_TRY) {
		UnsetSignUpSessions();
		print("0|인증번호 입력 횟수를 초과하였습니다.\n이메일 인증을 다시 시도해 주세요.");
		exit;
	}
	printf("2|인증번호가 옳바르지 않습니다.\n(%d/%d)",$_SESSION['auth_try'],MAX_AUTH_TRY);
	exit;
}

$DB = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,true);
if(!$DB) {
	print("2|데이터베이스에 접속하지 못했습니다.");
	exit;
}
mysql_select_db('samp_larp',$DB);

$name = mysql_real_escape_string($_POST['name']);
$password = mysql_real_escape_string($_POST['password']);
$password_reinput = mysql_real_escape_string($_POST['password_reinput']);

$check = CheckUserName($name,$DB);
if(!$check[0]) {
	$text = null;
	for($i = 1; $i < count($check); $i++)
		if(!$check[$i]) {
			$text .= sprintf("%d번, ",$i);
		}
	printf("0|닉네임 규칙 %s을 만족하지 못하였습니다.\n다른 닉네임을 입력해 주세요!",substr($text,0,strlen($text)-2));
	exit;
}
else if(strlen($password) < 4) {
	print("2|비밀번호가 4자리 이상이어야 합니다.");
	exit;
}
else if(strcmp($password,$password_reinput) != 0) {
	print("2|비밀번호와 비밀번호 확인이 일치해야 합니다.");
	exit;
}

$query = mysql_query("INSERT INTO register_queue (Email,Username,IP) VALUES ('$email','$name','$_SERVER[REMOTE_ADDR]')",$DB);
if(!$query) {
	print("2|회원가입을 처리하지 못했습니다!");
	exit;
}
print("1|$name의 회원가입 신청이 전송되었습니다.\n회원가입 결과는 담당자가 검토한 후 이메일로 전송해 드리겠습니다.");

?>