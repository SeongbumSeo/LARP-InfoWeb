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
|| !isset($_POST['number'])) {
	print("2|데이터를 전송받지 못했습니다.");
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
$number = mysql_real_escape_string($_POST['number']);
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

$query = mysql_query(sprintf("SELECT * FROM auth_data WHERE PhoneNumber = '%s' AND Passed = 1",$number),$DB);
if(!$query) {
	print("2|인증번호 발송에 실패했습니다.");
	exit;
}
if(mysql_num_rows($query) > 0) {
	print("2|해당 전화번호로 이미 인증된 계정이 있습니다.");
	exit;
}

$query = mysql_query(sprintf("INSERT INTO auth_data (PhoneNumber,Username,Code,Passed) VALUES ('%s','%s',%d,0)",
   $number,$name,$code),$DB);
if(!$query) {
	print("2|인증번호 발송에 실패했습니다.");
	exit;
}

$url = SMS_SEND_URL;
$url = parse_url($url);
$host = $url['host'];
$path = $url['path'];
$param = "number=$number&text=LA:RP 회원가입 인증번호는 [$code]입니다.";

$fp = fsockopen($host,80,$errno,$errstr,30);
if(!is_resource($fp)) {
	print("2|인증번호 발송에 실패했습니다.");
	exit;
}

fputs($fp,"POST $path HTTP/1.0\r\n");
fputs($fp,"Host: $host\r\n");
fputs($fp,"Content-type: application/x-www-form-urlencoded\r\n");
fputs($fp,"Content-length: ".strlen($param)."\r\n");
fputs($fp,"Connection: close\r\n");
fputs($fp,"\r\n");
fputs($fp,"$param\r\n");
fputs($fp,"\r\n");

while(!feof($fp))
	$file .= fgets($fp,1024);
fclose($fp);

$_SESSION['smsauth_code'] = $code;
$_SESSION['smsauth_time'] = time();
print("1|인증번호를 발송했습니다.");
?>