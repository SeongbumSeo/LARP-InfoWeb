<?php
require("initial.inc.php");
if($_GET['style']) {
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko" style="background: none;">
	<head>
<?php
	require("head.inc.php");
?>
	</head>
	<body>
<?php
}
switch($_GET['func']) {
    case 'mailaddress':
        if(AccessAdminKit($UserData['Admin'], 3)) {
            $dest = $DB->real_escape_string($_GET['dest']);
            
            if(strlen($dest) < 1) {
                print("양식을 모두 입력하세요.");
                exit;
            }
            
            $query = $DB->query(sprintf("
                SELECT
                    a.Username,
                    b.Content
                FROM
                    user_data a,
                    userauth_passed b
                WHERE
                    a.ID = b.UserID
                    AND b.Method = 1
                    AND (
                        a.Username = '%s'
                        OR a.ID = %d
                        OR b.Content = '%s'
                    )
                ", $dest, (int)$dest, $dest));
            if($query->num_rows < 1) {
                InsertLog($UserData, "Admin", sprintf("메일주소 조회: %s", $dest), false);
                printf("<b>%s</b> 메일주소를 찾을 수 없습니다.", $dest);
                exit;
						}
						$data = $query->fetch_row();
            $destname = $data[0];
            $email = $data[1];
            InsertLog($UserData, "Admin", sprintf("메일주소 조회: %s", $dest), true);
            printf("<b>%s</b>\t%s", $destname, $email);
        }
        break;
	case 'offlineprocess':
		if(AccessAdminKit($UserData['Admin'], 5)) {
			$name = str_replace('/', ',', $DB->real_escape_string($_GET['username']));
			$process = $DB->real_escape_string($_GET['process']);
			$value = $DB->real_escape_string($_GET['value']);
			$reason = $DB->real_escape_string($_GET['reason']);

			if(strlen($name) < 1 || strlen($process) < 1 || strlen($value) < 1 || strlen($reason) < 1) {
				print("양식을 모두 입력하세요.");
				exit;
			}
			if($process == "메시지") {
				$value = $DB->real_escape_string($adminname);
			} else {
				$value = (int)$value;
			}
			
			$logdata = sprintf("%s/%d/%s", str_replace('/', ',', $process), $value, str_replace('/', ',', $reason));
			$query = $DB->query(sprintf("SELECT ID, Username FROM user_data WHERE Username = '%s' OR ID = %d", $name, (int)$name));
			if($query->num_rows < 1) {
				InsertLog($UserData, "Admin", sprintf("오프라인 프로세스: NULL/%s/%s", $name, $logdata), false);
				printf("<b>%s</b> 계정을 찾을 수 없습니다.", $name);
				exit;
			}
			$data = $query->fetch_row();
			$destid = $data[0];
			$destname = $data[1];
			$query = $DB->query(sprintf("
				INSERT INTO offline_process (UserID, Confirmed, Contents, Function, Admin, Time) VALUES (%d, 0, '%s', '%s, %s', '%s', CURRENT_TIMESTAMP)", 
				$destid, $reason, $process, $value, $DB->real_escape_string($adminname)));
			InsertLog($UserData, "Admin", sprintf("오프라인 프로세스: %d/%s/%s", $destid, $destname, $logdata), ($query)? true: false);
			if($query)
				printf("오프라인 프로세스 성공: %s에게 %s (값: %s, 이유: %s)", $destname, $process, $value, $reason);
			else
				printf("오프라인 프로세스 실패");
		}
		break;
	case 'idunban':
		if(AccessAdminKit($UserData['Admin'], 5)) {
			if($_GET['data'] == "idunban") {
				$DB->query("UPDATE ban_data SET Valid = 0, ReleaseReason = '".$adminname.": ".$DB->real_escape_string($_POST['idunban_reason'])."' WHERE ID = ".(int)$_GET['dest']." AND IDBan = 1");
				$unbanresult = $DB->query("SELECT * FROM ban_data WHERE IDBan = 1 AND Valid = 0 AND ID = ".(int)$_GET['dest']);
				if($unbandata = $unbanresult->fetch_assoc())
					InsertLog($UserData, "Admin", "ID밴 해제: ".$unbandata['Username']." / ".$unbandata['IP']." / ".$unbandata['ReleaseReason'], true);
				else
					InsertLog($UserData, "Admin", "ID밴 해제: ".$unbandata['Username']." / ".$unbandata['IP']." / ".$unbandata['ReleaseReason'], false);
			}
			else if($_GET['data'] == "idbansearch")
			{
				$dest = $DB->real_escape_string($_POST['idunban_dest']);
				InsertLog($UserData, "Admin", "ID밴 조회: ".$dest, true);
			}
?>
				<form name="idunbansearch" action="adminparsing.php?func=idunban&data=idbansearch&style=true" method="post">
					<table class="simpleform">
						<tr>
							<td>
								<label for="idbansearch_inp_dest" class="unban_lbl normallbl"><?=(($_GET['data'] == "idbansearch") ? $dest : "닉네임")?></label>
								<input id="idbansearch_inp_dest" class="unban_inp" name="idunban_dest" type="text" />
							</td>
							<td>
								<button class="unban_sub">조회</button>
							</td>
						</tr>
					</table>
				</form>
				<div style="height: 2px;">&nbsp;</div>
				<table class="unban">
					<tbody id="idunban_list">
						<tr>
							<th>Username</th>
							<th>IP</th>
							<th colspan="2">Reason</th>
						</tr>
						<tr>
							<th>Host</th>
							<th>Time</th>
							<th>Until</th>
							<th>Valid</th>
						</tr>
						<tr>
						</tr>
<?php
			if($_GET['data'] == "idbansearch")
				$unbanresult = $DB->query("SELECT * FROM ban_data WHERE Username LIKE '".$dest."' AND IDBan = 1 ORDER BY Time DESC");
			else
				$unbanresult = $DB->query("SELECT * FROM ban_data WHERE IDBan = 1 ORDER BY Time DESC LIMIT 100");
				//$unbanresult = $DB->query("SELECT * FROM ban_data WHERE IDBan = 1 AND (Until > CURRENT_TIMESTAMP OR Time > date_add(now(), INTERVAL -1 MONTH)) ORDER BY Time DESC");
			$unbanrows = $unbanresult->num_rows;
			for($i = 0; $unbandata[$i] = $unbanresult->fetch_array(); $i++) {
?>
						<tr>
							<td><?=$unbandata[$i]['Username']?></td>
							<td><?=$unbandata[$i]['IP']?></td>
							<td colspan="2"><?=$unbandata[$i]['Reason']?></td>
						</tr>
						<tr>
							<td><?=$unbandata[$i]['Host']?></td>
							<td><?=$unbandata[$i]['Time']?></td>
							<td><?=((strcmp($unbandata[$i]['Until'], "0000-00-00 00:00:00") == 0) ? "무기한" : $unbandata[$i]['Until'])?></td>
							<td><?=(($unbandata[$i]['Valid'] == 1) ? "유효함" : "해제됨(".htmlspecialchars($unbandata[$i]['ReleaseReason']).")")?></td>
						</tr>
						<tr>
							<td colspan="4">
								<form name="idunban<?=$i?>frm" action="adminparsing.php?func=idunban&data=idunban&style=true&dest=<?=$unbandata[$i]['ID']?>" method="post">
									<table class="simpleform">
										<tr>
											<td>
												<label for="idunban<?=$i?>_inp_reason" class="unban_lbl normallbl">해제 사유</label>
												<input id="idunban<?=$i?>_inp_reason" class="unban_inp" name="idunban_reason" type="text" />
											</td>
											<td>
												<button class="unban_submit">해제</button>
											</td>
										</tr>
									</table>
								</form>
							</td>
						</tr>
<?php
			}
?>
					</tbody>
				</table>
				<div class="unban_list_nav"></div>
<?php
		}
		break;
	case 'ipunban':
		if(AccessAdminKit($UserData['Admin'], 5)) {
			if($_GET['data'] == "ipunban") {
				$DB->query("UPDATE ban_data SET Valid = 0, ReleaseReason = '".$adminname.": ".$DB->real_escape_string($_POST['ipunban_reason'])."' WHERE ID = ".(int)$_GET['dest']." AND IDBan = 0");
				$unbanresult = $DB->query("SELECT * FROM ban_data WHERE IDBan = 0 AND Valid = 0 AND ID = ".(int)$_GET['dest']);
				if($unbandata = $unbanresult->fetch_assoc())
					InsertLog($UserData, "Admin", "IP밴 해제: ".$unbandata['Username']." / ".$unbandata['IP']." / ".$unbandata['ReleaseReason'], true);
				else
					InsertLog($UserData, "Admin", "IP밴 해제: ".$unbandata['Username']." / ".$unbandata['IP']." / ".$unbandata['ReleaseReason'], false);
			}
			else if($_GET['data'] == "ipbansearch") {
				$dest = $DB->real_escape_string($_POST['ipunban_dest']);
				InsertLog($UserData, "Admin", "IP밴 조회: ".$dest, true);
			}
?>
				<form name="ipunbansearch" action="adminparsing.php?func=ipunban&data=ipbansearch&style=true" method="post">
					<table class="simpleform">
						<tr>
							<td>
								<label for="ipbansearch_inp_dest" class="unban_lbl normallbl"><?=(($_GET['data'] == "ipbansearch") ? $dest : "닉네임 혹은 IP")?></label>
								<input id="ipbansearch_inp_dest" class="unban_inp" name="ipunban_dest" type="text" />
							</td>
							<td>
								<button class="unban_sub">조회</button>
							</td>
						</tr>
					</table>
				</form>
				<div style="height: 2px;">&nbsp;</div>
				<table class="unban">
					<tbody id="ipunban_list">
						<tr>
							<th>Username</th>
							<th>IP</th>
							<th colspan="2">Reason</th>
						</tr>
						<tr>
							<th>Host</th>
							<th>Time</th>
							<th>Until</th>
							<th>Valid</th>
						</tr>
						<tr>
						</tr>
<?php
			if($_GET['data'] == "ipbansearch")
				$unbanresult = $DB->query("SELECT * FROM ban_data WHERE (Username LIKE '".$dest."' OR IP LIKE '".$dest."') AND IDBan = 0 ORDER BY Time DESC");
			else
				$unbanresult = $DB->query("SELECT * FROM ban_data WHERE IDBan = 0 ORDER BY Time DESC LIMIT 100");
				//$unbanresult = $DB->query("SELECT * FROM ban_data WHERE IDBan = 0 AND (Until > CURRENT_TIMESTAMP OR Time > date_add(now(), INTERVAL -1 MONTH)) ORDER BY Time DESC");
			for($i = 0; $unbandata[$i] = $unbanresult->fetch_array(); $i++) {
?>
						<tr>
							<td><?=$unbandata[$i]['Username']?></td>
							<td><?=$unbandata[$i]['IP']?></td>
							<td colspan="2"><?=$unbandata[$i]['Reason']?></td>
						</tr>
						<tr>
							<td><?=$unbandata[$i]['Host']?></td>
							<td><?=$unbandata[$i]['Time']?></td>
							<td><?=((strcmp($unbandata[$i]['Until'], "0000-00-00 00:00:00") == 0) ? "무기한" : $unbandata[$i]['Until'])?></td>
							<td><?=(($unbandata[$i]['Valid'] == 1) ? "유효함" : "해제됨(".htmlspecialchars($unbandata[$i]['ReleaseReason']).")")?></td>
						</tr>
						<tr>
							<td colspan="4">
								<form name="ipunban<?=$i?>frm" action="adminparsing.php?func=ipunban&data=ipunban&style=true&dest=<?=$unbandata[$i]['ID']?>" method="post">
									<table class="simpleform">
										<tr>
											<td>
												<label for="ipunban<?=$i?>_inp_reason" class="unban_lbl normallbl">해제 사유</label>
												<input id="ipunban<?=$i?>_inp_reason" class="unban_inp" name="ipunban_reason" type="text" />
											</td>
											<td>
												<button class="unban_submit">해제</button>
											</td>
										</tr>
									</table>
								</form>
							</td>
						</tr>
<?php
			}
?>
					</tbody>
				</table>
				<div class="unban_list_nav"></div>
<?php
		}
		break;
    case 'killlog':
        if(AccessAdminKit($UserData['Admin'],3)) {
            $type = $DB->real_escape_string($_GET['data']);
            $dest = $DB->real_escape_string($_GET['dest']);
            
            if(!strcmp($type, "user"))
                $typetext = "피해";
            else if(!strcmp($type, "killer"))
                $typetext = "가해";
            else exit;
            
            $query = $DB->query(sprintf("SELECT ID, Username FROM user_data WHERE ID = %d OR Username = '%s'", (int)$dest, $dest));
            if(!$query || $query->num_rows < 1) {
errHandler:
                InsertLog($UserData, "Admin", sprintf("킬 로그 조회(%s자): %s", $typetext, $dest), false);
                printf("<b>%s</b> %s자 킬 로그를 찾을 수 없습니다.", $dest, $typetext);
                exit;
            }
						$data = $query->fetch_row();
            $destid = $data[0];
            $destname = $data[1];
            
            if(!strcmp($type, "user")) {
                $username = $destname;
                $dbsearch = "UserID";
            }
            else {
                $killername = $destname;
                $dbsearch = "KillerID";
            }

            InsertLog($UserData, "Admin", sprintf("킬 로그 조회(%s자): %s", $typetext, $dest), true);
            $query = $LOG->query(sprintf("
                SELECT
                    *,
                    CONCAT (
                        X, ',', Y, ',', Z, ', ',
                        Interior, ', ', VirtualWorld
                    ) AS Position
                FROM
                    _log_kill
                WHERE
                    %s = %d
                ORDER BY Time DESC
                ", $dbsearch, $destid));
            if(!$query)
                goto errHandler;
            
            printf("
                <table class=\"killlog\">
                    <tbody>
                        <tr>
                            <th>User</th>
                            <th>Killer</th>
                            <th>Contents</th>
                        </tr>
                        <tr>
                            <th colspan=\"2\">Pos, Interior, VirtualWorld</th>
                            <th>Time</th>
                        </tr>
                ");
            while($log = $query->fetch_array()) {
                $spquery = $DB->query(sprintf("SELECT Username FROM user_data WHERE ID = %d",
                    (!strcmp($type, "user")) ? $log['KillerID'] : $log['UserID']));
                $numrows = $spquery->num_rows;
                $unknown = "<span style=\"color: #AA0000;\">UNKNOWN</span>";
								$data = $spquery->fetch_row();
                if(!strcmp($type, "user"))
                    $killername = ($numrows) ? $data[0] : $unknown;
                else
                    $username = ($numrows) ? $data[0] : $unknown;
                printf("
                    <tr>
                        <td>%s</td>
                        <td>%s</td>
                        <td>%s</td>
                    </tr>
                    <tr>
                        <td colspan=\"2\">%s</td>
                        <td>%s</td>
                    </tr>
                    ", $username, $killername, $log['Contents'], $log['Position'], $log['Time']);
            }
            printf("
                    </tbody>
                </table>
                "); 
        }
        break;
	case 'carblowlog':
		if(AccessAdminKit($UserData['Admin'], 3)) {
			unset($vehiclenumber);
			unset($username);
			if(!strcmp($_GET['data'], "vehicle")) {
				$vehicle = $DB->real_escape_string($_GET['vehicle']);
				$query = $DB->query(sprintf("SELECT ID, NumberPlate FROM car_data WHERE ID = %d OR NumberPlate = '%s'", 
			  		(int)$vehicle, str_replace("LA", "LA ", str_replace("LA ", "LA", $vehicle))));
				if($query->num_rows < 1) {
					InsertLog($UserData, "Admin", sprintf("차량 블로우 로그 조회(유저): %s", $vehicle), false);
					printf("<b>%s</b> 차량을 찾을 수 없습니다.<br />DB ID가 아닌 차량 번호를 키워드로 검색하시는 경우 <b>LA %d</b>를 검색해 보십시오.", 
				   		addslashes($vehicle), (int)$vehicle);
					exit;
				}
				$data = $query->fetch_row();
				$vehicle = $data[0];
				$vehiclenumber = $data[1];
				
				InsertLog($UserData, "Admin", sprintf("차량 블로우 로그 조회(차량): %s", $vehicle), true);
				$query = $LOG->query(sprintf("SELECT * FROM _log_carblow WHERE CarID = %d ORDER BY Time DESC", $vehicle));
			}
			else if(!strcmp($_GET['data'], "user")) {
				$query = $DB->query(sprintf("SELECT ID, Username FROM user_data WHERE ID = %d OR Username = '%s'", 
					(int)$_GET['user'], $DB->real_escape_string($_GET['user'])));
				if($query->num_rows < 1) {
					InsertLog($UserData, "Admin", sprintf("차량 블로우 로그 조회(유저): %s", $_GET['user']), false);
					printf("<b>%s</b> 유저를 찾을 수 없습니다.", addslashes($_GET['user']));
					exit;
				}
				$data = $query->fetch_row();
				$user = $data[0];
				$username = $data[1];
				
				InsertLog($UserData, "Admin", sprintf("차량 블로우 로그 조회(유저): %s", $_GET['user']), true);
				$query = $LOG->query(sprintf("SELECT * FROM _log_carblow WHERE UserID = %d ORDER BY Time DESC", $user));
			}
            else exit;
			if(isset($query)) {
				print("<table class=\"carblowlog\"><tbody><tr><th>CarID</th><th>CarNumber</ht><th>UserID</th><th colspan=\"2\">UserName</th></tr>");
				print("<tr><th colspan=\"2\">Pos, Interior, VirtualWorld</th><th>Memo</th><th>Time</th></tr>");
				while($log = $query->fetch_assoc()) {
					printf("<tr><td>%d</td><td>%s</td><td>%d</td><td colspan=\"2\">%s</td></tr> <tr><td colspan=\"2\">%s</td><td>%s</td><td>%s</td></tr>",
						$log['CarID'],
						isset($vehiclenumber)? $vehiclenumber: $DB->query(sprintf("SELECT NumberPlate FROM car_data WHERE ID = %d", $log['CarID']))->fetch_array()[0],
						$log['UserID'],
						isset($username)? $username: $DB->query(sprintf("SELECT Username FROM user_data WHERE ID = %d", $log['UserID']))->fetch_array()[0],
						$log['Pos'], $log['Memo'], $log['Time']);
				}
				print("</tbody></table>");
			}
		}
		break;
	case 'connectiplog':
		if(AccessAdminKit($UserData['Admin'], 3)) {
			$name = $DB->real_escape_string($_GET['name']);
			
			$query = $LOG->query("SELECT * FROM _log_connect WHERE Username LIKE '".$name."' OR IP LIKE '".$name."' ORDER BY Time DESC");
			
			InsertLog($UserData, "Admin", sprintf("접속 로그 조회: %s", $name), true);
			printf("<b>%s 접속 로그</b><br />", $name);
			while($log = $query->fetch_assoc())
				printf("Name: %s / IP: %s / Time: %s<br />", $log['Username'], $log['IP'], $log['Time']);
		}
		break;
	case 'changenamelog':
		if(AccessAdminKit($UserData['Admin'], 3)) {
			$keyword = $DB->real_escape_string($_GET['name']);
			$originalkeyword = $keyword;
			
			if(strlen($keyword) == 0) {
				exit;
			}
			else if(strpos($keyword, "User:") === 0) {
				$keyword = str_replace("User:", NULL, $keyword);
				$userquery = $DB->query(sprintf("SELECT ID, Username FROM user_data WHERE Username LIKE '%s' OR ID = %d", $keyword, (int)$keyword));
				if($userquery->num_rows < 1) {
					InsertLog($UserData, "Admin", sprintf("개명 로그 조회(특정 유저): %s", $keyword), false);
					printf("<b>%s</b> 키워드에 해당하는 계정을 찾을 수 없습니다.", $keyword);
					exit;
				}
				$data = $userquery->fetch_row();
				$query = $LOG->query(sprintf("SELECT * FROM _log_user WHERE Type=5 AND UserID = %d ORDER BY Time DESC", $data[0]));
				InsertLog($UserData, "Admin", sprintf("개명 로그 조회(특정 유저): %s", $data[1]), true);
			}
			else if(strpos($keyword, "Admin:") === 0) {
				$keyword = str_replace("Admin:", NULL, $keyword);
				$query = $LOG->query(sprintf("SELECT * FROM _log_user WHERE Type=5 AND Contents LIKE '%%(담당:%%%s%%)' ORDER BY Time DESC", $keyword));
				InsertLog($UserData, "Admin", sprintf("개명 로그 조회(담당자): %s", $keyword), true);
			}
			else {
				$query = $LOG->query(sprintf("SELECT * FROM _log_user WHERE Type=5 AND Contents LIKE '%%%s%%' ORDER BY Time DESC", $keyword));
				InsertLog($UserData, "Admin", sprintf("개명 로그 조회(키워드): %s", $keyword), true);
			}
			printf("<b>%s</b><br />", $originalkeyword);
			while($log = $query->fetch_assoc())
				printf("%s / Time: %s<br />", $log['Contents'], $log['Time']);
		}
		break;
	case 'checkuserdata':
		if(AccessAdminKit($UserData['Admin'], 6)) {
			$name = $DB->real_escape_string($_GET['name']);
			
			$fquery = $DB->query("SHOW COLUMNS FROM user_data");
			if(stripos($name, ':') > 0) {
				$inparr = explode(':', $name, 2);
				$uquery = $DB->query("SELECT * FROM user_data WHERE ".$inparr[0]." LIKE '".$inparr[1]."'");
			}
			else
				$uquery = $DB->query("SELECT * FROM user_data WHERE Username LIKE '".$name."' OR ID LIKE '".$name."'");
			if($uquery->num_rows < 1) {
				printf("<b>%s</b> 키워드에 해당하는 계정을 찾을 수 없습니다.", $name);
				exit;
			}
			else if($uquery->num_rows > 1) {
				printf("<b>%s</b> 키워드에 해당하는 계정이 %d개 있습니다.<br />", $name, $uquery)->num_rows;
				print("다음 중 한 계정을 다시 조회해 보십시오.<br /><br />");
				while($udata = $uquery->fetch_assoc())
					printf("ID: %d / Name: %s<br />", $udata['ID'], $udata['Username']);
				exit;
			}
			$udata = $uquery->fetch_array();
			
			print("<table class=\"databaseview\"><tbody><tr><th>Field</th><th>Type</th><th>Value</th></tr>");
			while($fdata = $fquery->fetch_assoc())
				if($fdata['Field'] != "Password")
					printf("<tr><td>%s</td><td>%s</td><td>%s</td></tr>", $fdata['Field'], $fdata['Type'], $udata[$fdata['Field']]);
			print("</tbody></table>");
		}
		break;
	/*case 'databaseview':
		if($UserData['ID'] == ACCOUNT_ACU || $UserData['ID'] == ACCOUNT_WLABYR) {
			$sql = $_GET['sql'];
			
			$query = $DB->query($sql);
			if(!$query)
				$query = $LOG->query($sql);
			if(!$query) {
				InsertLog($UserData, "Admin", sprintf("데이터베이스 조회: %s", $sql), false);
				printf("<b>%s</b><br />조회에 실패했습니다.", $sql);
				exit;
			}
			InsertLog($UserData, "Admin", sprintf("데이터베이스 조회: %s", $sql), true);
			if($query->num_rows < 1) {
				printf("<b>%s</b><br />조회 결과가 없습니다.", $sql);
				exit;
			}
			
			print("<table class=\"databaseview\"><tbody><tr>");
			for($i = 0; $fname = mysql_field_name($query, $i); $i++)
				printf("<th>%s</th>", $fname);
			print("</tr>");
			
			while($dbdata = $query->fetch_row()) {
				print("<tr>");
				for($i = 0; $i < count($dbdata); $i++)
					printf("<td>%s</td>", $dbdata[$i]);
				print("</tr>");
			}
			print("</tbody></table>");
		}
		break;*/
	/*case 'deleteaccount':
		if(AccessAdminKit($UserData['Admin'], 6)) {
			$name = $DB->real_escape_string($_GET['name']);
			
			$query = $DB->query("SELECT ID, Username FROM user_data WHERE Username = '".$name."' OR ID = '".$name."'");
			if($query->num_rows < 1) {
				InsertLog($UserData, "Admin", sprintf("계정 삭제: %s", $name), false);
				printf("<b>%s</b> 계정을 찾을 수 없습니다.", $name);
				exit;
			}
			$data = $query->fetch_row();
			$id = $data[0];
			$name = $data[1];
			$query = $DB->query("DELETE FROM user_data WHERE ID=".$id);
			InsertLog($UserData, "Admin", sprintf("계정 삭제: %s", $name), ($query)? true: false);
			if($query)
				printf("<b>%s</b> 계정을 삭제했습니다.", $name);
			else
				printf("<b>%s</b> 계정을 삭제하지 못했습니다.", $name);
		}
		break;*/
	case 'processlog':
		if(AccessAdminKit($UserData['Admin'], 6)) {
			$page = intval($_GET['page']);
			$name = $_GET['name'];
			$data = $_GET['data'];
			$pagesize = 10;
			
			$sql = "
				SELECT *
				FROM _ref_log_admin
				JOIN _log_admin ON _log_admin.Type = _ref_log_admin.Type
				WHERE _log_admin.Type >= 20 AND _log_admin.Type <= 24";
			$type = ($data == "user")? "담당자": "대상자";
			if(($data == "user" || $data == "dest") && strlen($name) > 0) {
				$udquery = $DB->query(sprintf("
					SELECT ID FROM user_data WHERE ID = '%d' OR Username LIKE '%s' OR AdminName LIKE '%s'", 
					intval($name), $name, $name));
				if($udquery->num_rows < 1) {
                    if(strlen($name))
					   InsertLog($UserData, "Admin", sprintf("프로세스 로그 조회(%s): %s", $type, $name), false);
					printf("<b>%s</b> 키워드에 해당하는 %s 계정을 찾을 수 없습니다.", $name, $type);
					break;
				}
				$sql .= sprintf(" AND %s = %d", ($data == "user") ? "UserID" : "DestID", $udquery->fetch_row()[0]);
			}
			$sql .= "
				ORDER BY Time DESC";
			$limit = sprintf(" LIMIT %d, %d", $page*$pagesize, $pagesize);
			$aquery = $LOG->query($sql.$limit);
			
            if(strlen($name))
                InsertLog($UserData, "Admin", sprintf("프로세스 로그 조회(%s): %s", $type, $name), true);
			printf("%d", $LOG->num_rows/$pagesize);
			while($adata = $aquery->fetch_assoc()) {
				$uquery = $DB->query("SELECT AdminName FROM user_data WHERE ID=".$adata['UserID']);
				$dquery = $DB->query("SELECT Username FROM user_data WHERE ID=".$adata['DestID']);
				// Type, AdminName, Username, Reason
				printf("|%s/%s/%s/%s", 
					$adata['Name'], str_replace('/', ',', $uquery->fetch_row()[0]), $dquery->fetch_row()[0], 
			   		str_replace('/', ',', ($adata['Type'] == 23 || $adata['Type'] == 24)? $adata['Value']: $adata['Contents']));
			}
		}
		break;
	case 'addoninsert':
		if (AccessAdminKit($UserData['Admin'], 7)) {
			$type = intval($_POST['type']);
			$name = $DB->real_escape_string($_POST['name']);
			$source = $DB->real_escape_string($_POST['source']);
			$description = $DB->real_escape_string($_POST['description']);
			
			$sql = sprintf("
				INSERT INTO launcher_addon_data
					(Type, Name, Source, Description)
				VALUES
					(%d, '%s', '%s', '%s')
				", $type, $name, $source, $description);
			$query = $DB->query($sql);
			
			InsertLog($UserData, "Admin", sprintf("애드온 추가: %s", $name), ($query) ? true : false);
			if ($query)
				printf("<b>%s</b> 애드온을 추가했습니다.", $name);
			else
				printf("<b>%s</b> 애드온을 추가하지 못했습니다.", $name);
		}
		break;
	case 'addondelete':
		if (AccessAdminKit($UserData['Admin'], 7)) {
			$id = intval($_POST['id']);
			$name = $_POST['name'];
			
			$sql = sprintf("SELECT Name FROM launcher_addon_data WHERE ID = %d", $id);
			$query = $DB->query($sql);
			if($query->num_rows < 1) {
				InsertLog($UserData, "Admin", sprintf("애드온 삭제: %s", $name), false);
				printf("<b>%s</b> 애드온을 찾을 수 없습니다.", $name);
				exit;
			}
			$name = $query->fetch_row()[0];
			
			$sql = sprintf("DELETE FROM launcher_addon_data WHERE ID = %d", $id);
			$query = $DB->query($sql);
			
			InsertLog($UserData, "Admin", sprintf("애드온 삭제: %s", $name), ($query) ? true : false);
			if ($query)
				printf("<b>%s</b> 애드온을 삭제했습니다.", $name);
			else
				printf("<b>%s</b> 애드온을 삭제하지 못했습니다.", $name);
		}
		break;
	case 'addonlist':
		if (AccessAdminKit($UserData['Admin'], 7)) {
			$page = intval($_GET['page']);
			$pagesize = 10;
			
			$sql = sprintf("
				SELECT *
				FROM launcher_addon_data
				WHERE Deleted = 0
				ORDER BY CreatedTime DESC
				LIMIT %d, %d
				", $page*$pagesize, $pagesize);
			$query = $DB->query($sql);
			
			$data = [];
			while ($datum = $query->fetch_assoc()) {
				array_push($data, $datum);
			}
			print(json_encode($data));
		}
		break;
	default:
		printf("%s는 없는 기능입니다.", $_GET['func']);
		break;
}
if($_GET['style']) {
?>
	</body>
</html>
<?php
}
?>