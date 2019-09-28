<?php
include "./initial.inc.php";

function AccessAdminKit($adminlevel, $accesslevel, $showmessage=true)
{
	if($adminlevel >= $accesslevel) return true;
	else if($showmessage)
	{
		echo "<center>이 키트에 접근하려면 권한".$accesslevel." 이상 필요합니다.</center>";
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
?>
		<title>계정 고유번호 조회 - LA:RP Information Website</title>
	</head>
	<body style="background-color: #FFF;">
<?php
if(AccessAdminKit($data['Admin'], 7)) {
?>
		<form name="useridfrm" action="useridsearch.php" method="get">
			<table class="useridtable">
				<tbody>
					<tr>
						<td>
							<label to="userid_inp_name" class="userid_lbl">닉네임</label>
							<input id="userid_inp_name" class="userid_inp" name="userid_name" type="text" />
						</td>
						<td>
							<button class="userid_sub">조회</button>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
<?php
	if(strlen($_GET['userid_name']) > 0) {
		$useridresult = @$MySQL->query("SELECT Username, ID FROM user_data WHERE Username LIKE '".$DB->real_escape_string(htmlspecialchars_decode($_GET['userid_name']))."'");
		for($i = 0; $useriddata[$i] = @$useridresult->fetch_row(); $i++) {
?>
		<p style="padding-left: 10px;"><b><?=$useriddata[$i][0]?></b>: <?=$useriddata[$i][1]?></p>
<?php
		}
	}
}
?>
	</body>
</html>