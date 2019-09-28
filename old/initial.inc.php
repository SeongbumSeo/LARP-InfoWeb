<?php
session_start();

require("classes/HTMLParser/HTMLParser.class.php");
require("defines.inc.php");

if(TESTMODE) {
	if(strcmp($_SERVER['REMOTE_ADDR'],TESTERIP)) {
		printf("<center style=\"color: #000;\">인포웹 점검중입니다.<br />당신의 IP: %s</center>",$_SERVER['REMOTE_ADDR']);
		exit;
	}
}

$DB = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD);
$LOG = new mysqli(DB_HOST,DB_USERNAME,DB_PASSWORD);
if($DB->connect_error || $LOG->connect_error) {
	echo "데이터베이스 접속 실패.";
	header("refresh: 0");
	exit;
}
$DB->set_charset("utf8");
$LOG->set_charset("utf8");
$DB->select_db('samp_larp');
$LOG->select_db('samp_larp');

require("functions.inc.php");
require("banned.inc.php");

if(strcasecmp($_SERVER['PHP_SELF'],"/old/login.php") != 0 && strcasecmp($_SERVER['PHP_SELF'],"/old/signup.php") != 0) {
	if(!IsUserLoggedIn()) {
		header("location: ".HTTP_HOST."/old/login.php");
		exit;
	}
	else {
		$result = $DB->query("
			SELECT
				*
			FROM
				user_data
			WHERE
				Username = '$_SESSION[username]'
				AND Password = '$_SESSION[password]'
				AND Deprecated = 0
			LIMIT 1");
		$UserData = $result->fetch_array();

		if(isset($_GET['logout']) || !$UserData) {
			session_unset();
			session_destroy();
			header("location: ".HTTP_HOST."/old/login.php");
			exit;
		}

		// 유저 닉네임
		$username = str_replace('_',' ',$UserData['Username']);
		$adminname = ($UserData['Admin'] > 0) ? $UserData['AdminName'] : $username;
	}
}
else if(IsUserLoggedIn()) {
	header("location: ".HTTP_HOST);
	exit;
}
?>