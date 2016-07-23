<?php
session_start();

require('mysqli.php');

switch((int)$_POST['cmd']) {
	case 0:
		if(!isset($_SESSION['id']))
			print("0");
		else {
			$id = $_SESSION['id'];
			$username = $_SESSION['username'];
			$password = $_SESSION['password'];

			$result = $db_samp->query("
				SELECT
					NULL
				FROM
					user_data
				WHERE
					ID = $id
					AND Username = '$username'
					AND Password = '$password'
				ORDER BY CreatedTime ASC
				LIMIT 1");
			print($result->num_rows);
		}
		break;
	case 1:
		$username = $db_samp->real_escape_string(str_replace(' ', '_', $_POST['username']));
		$password = $db_samp->real_escape_string($_POST['password']);

		$result = $db_samp->query("
			SELECT
				ID,
				Username,
				Password
			FROM
				user_data
			WHERE
				Deprecated = 0
				AND (
					Username = '$username'
					OR (AdminName = '$username' AND Admin > 0)
				)
				AND Password = SHA1('$password')
			ORDER BY CreatedTime ASC
			LIMIT 1");
		if(isset($_SESSION['logintry']) && $_SESSION['logintry'] > 3)
			print("0|로그인을 3회 이상 실패하였습니다.");
		else if(strlen($username) < 1 && strlen($password) < 1)
			print("0|닉네임과 비밀번호를 입력하십시오.");
		else if(strlen($username) < 1)
			print("0|닉네임을 입력하십시오.");
		else if(strlen($password) < 1)
			print("0|비밀번호를 입력하십시오.");
		else if($data = $result->fetch_array()) {
			$_SESSION['id'] = $data['ID'];
			$_SESSION['username'] = $data['Username'];
			$_SESSION['password'] = $data['Password'];
			print("1");
		}
		else {
			if(!isset($_SESSION['logintry']))
				$_SESSION['logintry'] = 0;
			$_SESSION['logintry']++;
			printf("0|닉네임 혹은 비밀번호를 확인하십시오. (%d/3회)", $_SESSION['logintry']);
		}
		break;
	case 2:
		unset($_SESSION['id']);
		unset($_SESSION['username']);
		unset($_SESSION['password']);
		print("1");
		break;
}
?>