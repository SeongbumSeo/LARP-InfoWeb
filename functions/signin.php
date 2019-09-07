<?php
session_start();

require_once('../config.php');
require_once('mysqli.php');

define("CMD_GET_SIGNED_STATUS",	0);
define("CMD_REQUEST_SIGN_IN",	1);
define("CMD_SIGN_OUT",			2);

switch((int)$_POST['cmd']) {
	case CMD_GET_SIGNED_STATUS:
		if(!isset($_SESSION['id']))
			print("0");
		else {
			$id = $_SESSION['id'];
			$username = $_SESSION['username'];
			$password = $_SESSION['password'];

			$result = $mysqli->query("
				SELECT
					NULL
				FROM
					".DB_LARP.".user_data
				WHERE
					ID = $id
					AND Username = '$username'
					AND Password = '$password'
				ORDER BY CreatedTime ASC
				LIMIT 1");
			print($result->num_rows);
		}
		break;
	case CMD_REQUEST_SIGN_IN:
		$username = $mysqli->real_escape_string(str_replace(' ', '_', $_POST['username']));
		$password = $mysqli->real_escape_string($_POST['password']);
		$ip = $_SERVER['REMOTE_ADDR'];

		$loginResult = $mysqli->query("
			SELECT
				ID,
				Username,
				Password
			FROM
				".DB_LARP.".user_data
			WHERE
				Deprecated = 0
				AND (
					Username = '$username'
					OR (AdminName = '$username' AND Admin > 0)
				)
				AND Password = SHA1('$password')
			ORDER BY CreatedTime ASC
			LIMIT 1");
		$loginTryResult = $mysqli->query("
			SELECT
				COUNT(*)
			FROM
				".DB_LARP.".infoweb_login_try_data
			WHERE
				IP = '$ip'
				AND TIMESTAMPDIFF(MINUTE, NOW(), Time) < 10");
		$numLoginTry = $loginTryResult->fetch_array()[0];

		if(isset($numLoginTry) && $numLoginTry > 3) {
			print("0|로그인을 3회 이상 실패하였습니다.");
		} else if(strlen($username) < 1 && strlen($password) < 1) {
			print("0|닉네임과 비밀번호를 입력하십시오.");
		} else if(strlen($username) < 1) {
			print("0|닉네임을 입력하십시오.");
		} else if(strlen($password) < 1) {
			print("0|비밀번호를 입력하십시오.");
		} else if($data = $loginResult->fetch_array()) {
			$_SESSION['id'] = $data['ID'];
			$_SESSION['username'] = $data['Username'];
			$_SESSION['password'] = $data['Password'];
			print("1");
		} else {
			$mysqli->query("
				INSERT INTO ".DB_LARP.".infoweb_login_try_data (IP, Username)
				VALUES ('$ip', '$username')");
			printf("0|닉네임 혹은 비밀번호를 확인하십시오. (%d/3회)", ++$numLoginTry);
		}
		break;
	case CMD_SIGN_OUT:
		unset($_SESSION['id']);
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		print("1");
		break;
}
?>