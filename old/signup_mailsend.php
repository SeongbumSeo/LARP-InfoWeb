<?php
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
header('Cache-Control: no-cache, must-revalidate'); 
header('Pragma: no-cache');
header('Content-Type: text/html');

session_start();

require('defines.inc.php');
require('signup_functions.inc.php');

if(!isset($_POST['name'])
|| !isset($_POST['password'])
|| !isset($_POST['password_reinput'])
|| !isset($_POST['email'])) {
	print("2|데이터를 전송받지 못했습니다.");
	exit;
}

$DB = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,true);
if(!$DB) {
	print("2|데이터베이스에 접속하지 못했습니다.");
	exit;
}
mysql_select_db('samp_larp',$DB);

$name = $DB->real_escape_string($_POST['name']);
$password = $DB->real_escape_string($_POST['password']);
$password_reinput = $DB->real_escape_string($_POST['password_reinput']);
$email = $DB->real_escape_string($_POST['email']);
$code = mt_rand(10000,99999);

$check = CheckUserName($name,$DB);
if(!$check[0]) {
	$text = null;
	for($i = 1; $i < count($check); $i++)
		if(!$check[$i]) {
			$text .= sprintf("%d번, ",$i);
		}
	printf("2|닉네임 규칙 %s을 만족하지 못하였습니다.\n다른 닉네임을 입력해 주세요!",substr($text,0,strlen($text)-2));
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

$query = $DB->query("SELECT * FROM register_queue WHERE Email = '$email'");
if(!$query) {
	print("2|인증번호 발송에 실패했습니다.\n(가입 대기자 이메일 확인 불가)");
	exit;
}
if($query->num_rows > 0) {
	print("2|해당 이메일은 이미 가입 대기중입니다.");
	exit;
}

$query = $DB->query("SELECT * FROM register_queue WHERE Username = '$name'");
if(!$query) {
	print("2|인증번호 발송에 실패했습니다.\n(가입 대기자 닉네임 확인 불가)");
	exit;
}
if($query->num_rows > 0) {
	print("2|해당 닉네임은 이미 가입 대기중입니다.");
	exit;
}

$mailsubject = "[LA:RP] 계정 등록 인증 번호입니다.";
$mailbody = "
	안녕하세요! 가입 인증 번호는 아래와 같습니다.<br>
	<br>
	<b>	인증번호: </b>$code<br>
	<br>
	본 인증 번호는 5분간 유효합니다.<br>
	<br>
	Los Angeles Role Play<br>
	Support Center<br>
";

$mail = SendMail($email,$mailsubject,$mailbody);
if(!$mail->send()) {
	printf("2|인증번호 발송에 실패했습니다.\n(%s)",$mail->ErrorInfo);
	exit;
}

$_SESSION['email'] = $email;
$_SESSION['auth_code'] = $code;
$_SESSION['auth_time'] = time();
printf("1|%s로 인증번호를 발송했습니다.",$email);
?>