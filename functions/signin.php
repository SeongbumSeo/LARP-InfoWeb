<?php
session_start();

require('mysqli.php');

switch((int)$_POST['cmd']) {
	case 0:
		print((int)(isset($_SESSION['id']) && isset($_SESSION['password'])));
		break;
	case 1:
		$id = $db_samp->real_escape_string(str_replace(' ', '_', $_POST['id']));
		$password = $db_samp->real_escape_string($_POST['password']);

		$result = $db_samp->query("
			SELECT
				Username,
				Password
			FROM
				user_data
			WHERE
				Deprecated = 0
				AND (
					Username = '$id'
					OR (AdminName = '$id' AND Admin > 0)
				)
				AND Password = SHA1('$password')
			ORDER BY CreatedTime ASC");
		if(isset($_SESSION['logintry']) && $_SESSION['logintry'] > 3)
			print("0|로그인을 3회 이상 실패하였습니다.");
		else if(strlen($id) < 1 && strlen($password) < 1)
			print("0|닉네임과 비밀번호를 입력하십시오.");
		else if(strlen($id) < 1)
			print("0|닉네임을 입력하십시오.");
		else if(strlen($password) < 1)
			print("0|비밀번호를 입력하십시오.");
		else if($data = $result->fetch_array()) {
			$_SESSION['id'] = $data['Username'];
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
		unset($_SESSION['password']);
		print("1");
		break;
}
?>