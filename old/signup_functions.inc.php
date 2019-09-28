<?php
function UnsetSignUpSessions() {
	unset($_SESSION['email']);
	unset($_SESSION['auth_code']);
	unset($_SESSION['auth_time']);
	unset($_SESSION['auth_try']);
}

function CheckUserName($name,$resource) {
	/*
	1. _(밑줄)이 한 개 포함되어 있어야 합니다.
	2. 이름은 밑줄 포함 5자 이상, 24자 이하이어야 합니다.
	3. 알파벳 이외에 공백이나 숫자, 특수문자는 사용할 수 없습니다.
	4. 이름_성 형식에 맞춰야 하며, 성과 이름의 첫글자만 대문자여야 합니다.
	5. 성과 이름은 각각 2글자 이상이어야 합니다.
	6. 이미 가입된 닉네임과 중복되지 않아야 합니다.
	7. 다중계정 가입 승인된 닉네임이어야 합니다.
	*/
	$available = array_fill(0,7,true);
	$name_exploded = explode('_',$name);
	// 1
	$available[1] = (substr_count($name,'_') == 1);
	// 2
	$available[2] = (strlen($name) >= 5 && strlen($name) <= 24);
	// 3
	$available[3] = ctype_alpha(str_replace('_','',$name));
	// 4
	for($i = 0; $i < count($name_exploded); $i++) {
		if(strlen($name_exploded[$i]) < 1) {
			$available[4] = false;
			break;
		}
		for($j = 0; $j < strlen($name_exploded[$i]); $j++) {
			$character = substr($name_exploded[$i],$j,1);
			if(!ctype_alpha($character)
			|| ($j == 0 && ctype_lower($character)
			|| $j > 0 && ctype_upper($character))) {
				$available[4] = false;
				break;
			}
		}
		if(!$available[4])
			break;
	}
	// 5
	for($i = 0; $i < count($name_exploded); $i++)
		if(strlen($name_exploded[$i]) < 2) {
			$available[5] = false;
			break;
		}
	// 6
	$query = $resource->query("SELECT * FROM user_data WHERE Username = '$name'");
	$available[6] = ($query->num_rows === 0);
	// 7
	if(mysql_num_rows(GetSignedAccountsByIP($_SERVER['REMOTE_ADDR'],$resource)) > 0) {
		$available[7] = false;
		$query = $resource->query("SELECT Username FROM multiaccount_data WHERE IP = '$_SERVER[REMOTE_ADDR]'");
		foreach(mysql_fetch_row($query) as $maname)
			if(strcmp($name,$maname) == 0) {
				$available[7] = true;
				break;
			}
	}
	// 0
	for($i = 1; $i < count($available); $i++)
		if(!$available[$i]) {
			$available[0] = false;
			break;
		}
	
	return $available;
}

function GetSignedAccountsByIP($ip,$resource) {
	$query = $resource->query("
		SELECT
			a.Username
		FROM
			user_data a
			LEFT JOIN ban_data b
			ON (
				a.Username = b.Username
				AND b.Valid = 1
				AND b.Time < NOW()
				AND (
					b.Until > NOW()
					OR DATE(b.Until) = '0000-00-00'
				)
			)
		WHERE
			a.IP LIKE CONCAT(SUBSTRING_INDEX('$ip','.',3),'.%')
			AND b.Username IS NULL
	");
	return $query;
}
?>