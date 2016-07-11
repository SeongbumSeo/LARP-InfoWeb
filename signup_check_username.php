<?php
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); 
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); 
header('Cache-Control: no-cache, must-revalidate'); 
header('Pragma: no-cache');
header('Content-Type: text/html');

session_start();

require("defines.inc.php");
require("signup_functions.inc.php");

if(!isset($_POST['name'])) {
	print("데이터를 전송받지 못했습니다.");
	exit;
}

$DB = mysql_connect(DB_HOST,DB_USERNAME,DB_PASSWORD,true);
if(!$DB) {
	print("데이터베이스에 접속하지 못했습니다.");
	exit;
}
mysql_select_db('samp_larp',$DB);

$name = mysql_real_escape_string($_POST['name']);
$check = CheckUserName($name,$DB);
$color = array_fill(1,7,"#AA0000");
for($i = 1; $i < count($check); $i++)
	if($check[$i])
		$color[$i] = "#00AA00";

print("
<p style=\"color: $color[1]\">1. _(밑줄)이 한 개 포함되어 있어야 합니다.</p>
<p style=\"color: $color[2]\">2. 이름은 밑줄 포함 5자 이상, 24자 이하이어야 합니다.</p>
<p style=\"color: $color[3]\">3. 알파벳 이외에 공백이나 숫자, 특수문자는 사용할 수 없습니다.</p>
<p style=\"color: $color[4]\">4. 이름_성 형식에 맞춰야 하며, 성과 이름의 첫글자만 대문자여야 합니다.</p>
<p style=\"color: $color[5]\">5. 성과 이름은 각각 2글자 이상이어야 합니다.</p>
<p style=\"color: $color[6]\">6. 이미 가입된 닉네임과 중복되지 않아야 합니다.</p>
");
if(mysql_num_rows(GetSignedAccountsByIP($_SERVER['REMOTE_ADDR'],$DB)) > 0)
	print("<p style=\"color: $color[7]\">7. 다중계정 가입 승인된 닉네임이어야 합니다.</p>");
?>