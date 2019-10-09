<?php
include "./initial.inc.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
?>
		<title>Admin - LA:RP Information Website</title>
		<script type="text/javascript">
			var page_processlog = 0;
			var recent_processlog_data = "";
			var recent_processlog_name = "";
			
			$(document).ready(function() {
				$('.navl_admin').addClass("active");
				
				LoadProcessLog("null");
				
				$('button[id=mailaddress_sub]').click(function() {
						$.ajax({
							type: "get",
								url: "adminparsing.php?func=mailaddress&dest=" + $('#mailaddress_inp_dest').val(),
								cache: false,
								success: function(data) {
										$("#mailaddress").html(data);
								}
						});
				});
				$('#offlineprocess_process').change(function() {
					var disabled = (this.value == "메시지");
					$('#offlineprocess_value').prop('disabled', disabled);
					if (disabled) {
						$('#offlineprocess_value').val("<?=$adminname?>");
					}
				});
				$('button[id=offlineprocess_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=offlineprocess&process=" + encodeURIComponent($('#offlineprocess_process').val()) + "&value=" + $('#offlineprocess_value').val() + "&username=" + encodeURIComponent($('#offlineprocess_username').val()) + "&reason=" + encodeURIComponent($('#offlineprocess_reason').val()),
						cache: false,
						success: function(data) {
							$("#offlineprocess").html(data);
						}
					});
				});
				$('button[id=killlog_user_sub]').click(function() {
						$.ajax({
								type: "get",
								url: "adminparsing.php?func=killlog&data=user&dest=" + $('#killlog_inp_user').val(),
								cache: false,
								success: function(data) {
										$("#killlog").html(data);
								}
						});
				});
				$('button[id=killlog_killer_sub]').click(function() {
						$.ajax({
								type: "get",
								url: "adminparsing.php?func=killlog&data=killer&dest=" + $('#killlog_inp_killer').val(),
								cache: false,
								success: function(data) {
										$("#killlog").html(data);
								}
						});
				});
				$('button[id=carblowlog_vehicle_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=carblowlog&data=vehicle&vehicle=" + $('#carblowlog_inp_vehicle').val(),
						cache: false,
						success: function(data) {
							$("#carblowlog").html(data);
						}

					});
				});
				$('button[id=carblowlog_user_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=carblowlog&data=user&user=" + $('#carblowlog_inp_user').val(),
						cache: false,
						success: function(data) {
							$("#carblowlog").html(data);
						}

					});
				});
				$('button[id=connectiplog_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=connectiplog&name=" + $('#connectiplog_inp_dest').val(),
						cache: false,
						success: function(data) {
							$("#connectiplog").html(data);
						}

					});
				});
				$('button[id=changenamelog_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=changenamelog&name=" + $('#changenamelog_inp_dest').val(),
						cache: false,
						success: function(data) {
							$("#changenamelog").html(data);
						}

					});
				});
				$('button[id=checkuserdata_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=checkuserdata&name=" + $('#checkuserdata_inp_dest').val(),
						cache: false,
						success: function(data) {
							$("#checkuserdata").html(data);
						}

					});
				});
				$('button[id=databaseview_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=databaseview&sql=" + encodeURIComponent($('#databaseview_inp_sql').val()),
						cache: false,
						success: function(data) {
							$("#databaseview").html(data);
						}

					});
				});
				$('button[id=deleteaccount_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=deleteaccount&name=" + $('#deleteaccount_inp_dest').val(),
						cache: false,
						success: function(data) {
							$("#deleteaccount").html(data);
						}

					});
				});
				$('button[id=processlog_user_sub]').click(function() {
					page_processlog = 0;
					recent_processlog_data = "user";
					recent_processlog_name = $('#processlog_inp_user').val();
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=processlog&data=user&name=" + recent_processlog_name,
						cache: false,
						success: function(data) {
							LoadProcessLog(data);
						}
					});
				});
				$('button[id=processlog_dest_sub]').click(function() {
					page_processlog = 0;
					recent_processlog_data = "dest";
					recent_processlog_name = $('#processlog_inp_dest').val();
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=processlog&data=dest&name=" + recent_processlog_name,
						cache: false,
						success: function(data) {
							LoadProcessLog(data);
						}
					});
				});
			});
			function LoadProcessLog(logdata) {
				if(logdata == "null")
					$.ajax({
						type: "get",
						url: "adminparsing.php?func=processlog&page=" + page_processlog +
							"&data=" + recent_processlog_data + "&name=" + recent_processlog_name,
						cache: false,
						success: function(data) {
							LoadProcessLog(data);
						}
					});
				else {
					var tarr = logdata.split('|');
					var tval = "<tr><th>유형</th><th>담당자</th><th>대상자</th><th>사유</th></tr>";

					for(var i = 1; i < tarr.length; i++) {
						var list = tarr[i].split('/');
						tval += "<tr>";
						for(var j = 0; j < list.length; j++)
							tval += "<td>" + list[j] + "</td>";
						tval += "</tr>";
					}
					$('table[id=processlog]').html(tval);

					var pval = "";
					if(page_processlog > 0)
						pval += "<a href=\"javascript:page_processlog--;LoadProcessLog('null');\"><</a>&nbsp;";
					pval += "<input type=\"text\" />&nbsp;/&nbsp;";
					pval += tarr[0];
					if(page_processlog < tarr[0])
						pval += "&nbsp;<a href=\"javascript:page_processlog++;LoadProcessLog('null');\">></a>";
					$('div[id=processlog_nav]').html(pval);
				}
			}
		</script>
	</head>

	<body>
<?php
include "./navbar.inc.php";
?>
		<div id="contents">
<?php
include "./header.inc.php";
?>
			<div id="conbox" class="head" style="background-color: #AAA;">
				<h1>내 IP</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<p style="text-align: center; font-size: 1.2em;">현재 접속중인 IP는 <span style="color: #AA0000;"><?=$_SERVER['REMOTE_ADDR']?></span>입니다.</p>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>비밀번호 변경</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],5)) {
	if($_GET['func'] == "setpw") {
		$username = $DB->real_escape_string(htmlspecialchars_decode($_POST['setpw_dest']));
		$password = $DB->real_escape_string(htmlspecialchars_decode($_POST['setpw_pw']));
		$setpwresult = $DB->query("UPDATE user_data SET Password = SHA1('".$password."') WHERE Username = '".$username."'");
		InsertLog($UserData,"Admin",sprintf("비밀번호 변경 : %s",$username),($setpwresult)? true: false);
		printf("<script type=\"text/javascript\">alert(\"%s 비밀번호 변경 %s\");</script>",$username,($setpwresult)? "성공": "실패");
	}
?>
				<form name="setpwfrm" action="admin.php?func=setpw" method="post">
					<table class="setpw">
						<tbody>
							<tr>
								<th>대상 닉네임</th>
								<td><input id="setpw_dest" name="setpw_dest" type="text" /></td>
								<th>비밀번호</th>
								<td><input id="setpw_pw" name="setpw_pw" type="text" /></td>
							</tr>
							<tr>
								<td colspan="4"><button class="setpw_submit">변경</button></td>
							</tr>
						</tbody>
					</table>
				</form>
				<p class="summary">권한5 이상</p>
<?php
}
?>
			</div>
            
            <div id="conbox" class="head" style="background-color: #DDD;">
				<h1>메일주소 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],3)) {
?>
                <table class="simpleform">
                    <tbody>
                        <tr>
                            <td>
                                <label for="mailaddress_inp_dest" class="normallbl">닉네임 혹은 유저코드 혹은 이메일</label>
                                <input id="mailaddress_inp_dest" class="mailaddress_inp" type="text" />
                            </td>
                            <td>
                                <button id="mailaddress_sub">조회</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
				<div id="mailaddress" style="text-align: center;">
				</div>
				<p class="summary">권한3 이상</p>
<?php
}
?>
            </div>
			
			<!--<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>프로세스 로그</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],6)) {
?>
				<table class="simpleform">
					<tbody>
						<tr>
							<td style="border-bottom: none;">
								<label for="processlog_inp_user" class="normallbl">담당자 관리이름 혹은 닉네임 혹은 유저코드</label>
								<input id="processlog_inp_user" class="process_user_inp" type="text" />
							</td>
							<td style="border-bottom: none;">
								<button id="processlog_user_sub">조회</button>
							</td>
						</tr>
						<tr>
							<td>
								<label for="processlog_inp_dest" class="normallbl">대상자 닉네임 혹은 유저코드</label>
								<input id="processlog_inp_dest" class="process_dest_inp" type="text" />
							</td>
							<td>
								<button id="processlog_dest_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<table id="processlog"></table>
				<div id="processlog_nav"></div>
				<p class="summary">미완성</p>
<?php
}
?>
			</div>-->
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>오프라인 프로세스</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],5)) {
?>

				<table class="offlineprocesstable">
					<tbody>
						<tr>
							<th>대상 닉네임</th>
							<td><input id="offlineprocess_username" name="offlineprocess_username" type="text" /></td>
							<th>처분 내용</th>
							<td>
								<select id="offlineprocess_process">
									<option value="경고">경고</option>
									<option value="차감">차감</option>
									<option value="칭찬">칭찬</option>
									<option value="돈지급">돈지급</option>
									<option value="돈회수">돈회수</option>
									<option value="메시지">메시지</option>
								</select>
							</td>
							<th>값</th>
							<td><input id="offlineprocess_value" name="offlineprocess_value" type="text" /></td>
						</tr>
						<tr>
							<th>사유</th>
							<td colspan="5"><input id="offlineprocess_reason" name="offlineprocess_reason" type="text" /></td>
						</tr>
						<tr>
							<td colspan="6"><button id="offlineprocess_sub">적용</button></td>
						</tr>
					</tbody>
				</table>
				<div id="offlineprocess" style="text-align: center;">
				</div>
				<p class="summary">권한5 이상</p>
				<p class="summary">값: <u>처분 횟수</u> 또는 <u>금액</u></p>
<?php
}
?>
			</div>

			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>ID밴 추가</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],3)) {
	if($_GET['func'] == "idban" && strlen($_POST['idban_username']) > 0) {
		$bansql = "INSERT INTO ban_data (Username,IP,IDBan,Reason,Host,Time,Until,Valid) VALUES (";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode($_POST['idban_username']))."',";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode($_POST['idban_ip']))."',";
		$bansql .= "1,";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode($_POST['idban_reason']))."',";
		$bansql .= "'".$adminname."',";
		$bansql .= "CURRENT_TIMESTAMP,";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode((strcmp($_POST['idban_until'],"무기한") == 0) ? "0000-00-00 00:00:00" : $_POST['idban_until']))."',";
		$bansql .= "1)";
		$banresult = $DB->query($bansql);
		InsertLog($UserData,"Admin",sprintf("ID밴: %s / %s / %s",$_POST['idban_username'],$_POST['idban_ip'],$_POST['idban_reason']));
		printf("<script type=\"text/javascript\">alert(\"%s / %s ID밴 %s\");</script>",
  			addslashes($_POST['idban_username']),addslashes($_POST['idban_ip']),($banresult)? "성공": "실패");
	}
?>
				<form name="idbanfrm" action="admin.php?func=idban" method="post">
					<table class="ban">
						<tbody>
							<tr>
								<th>대상 닉네임</th>
								<td><input id="idban_username" name="idban_username" type="text" /></td>
								<th>IP</th>
								<td><input id="idban_ip" name="idban_ip" type="text" /></td>
								<th>만료기한</th>
								<td><input id="idban_until" name="idban_until" type="text" /></td>
							</tr>
							<tr>
								<th>차단사유</th>
								<td colspan="5"><input id="idban_reason" name="idban_reason" type="text" /></td>
							</tr>
							<tr>
								<td colspan="6"><button class="ban_submit">추가</button></td>
							</tr>
						</tbody>
					</table>
				</form>
				<p class="summary">권한3 이상</p>
				<p class="summary">만료기한은 <u>무기한</u> 혹은 <u>년-월-일 시:분:초</u>(ex.2009-01-12 21:15:00) 형식</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>ID밴 조회/해제</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],5)) {
?>
				<iframe id="iframe_idunban_list" src="adminparsing.php?func=idunban&style=true"></iframe>
				<p class="summary">권한5 이상</p>
				<p class="summary">조회 시 LIKE 사용(대체문자 %)</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>IP밴 추가</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],3)) {
	if($_GET['func'] == "ipban" && strlen($_POST['ipban_username']) > 0) {
		$bansql = "INSERT INTO ban_data (Username,IP,IDBan,Reason,Host,Time,Until,Valid) VALUES (";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode($_POST['ipban_username']))."',";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode($_POST['ipban_ip']))."',";
		$bansql .= "0,";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode($_POST['ipban_reason']))."',";
		$bansql .= "'".$adminname."',";
		$bansql .= "CURRENT_TIMESTAMP,";
		$bansql .= "'".$DB->real_escape_string(htmlspecialchars_decode((strcmp($_POST['ipban_until'],"무기한") == 0) ? "0000-00-00 00:00:00" : $_POST['ipban_until']))."',";
		$bansql .= "1)";
		$banresult = $DB->query($bansql);
		InsertLog($UserData,"Admin",sprintf("IP밴: %s / %s / %s",$_POST['ipban_username'],$_POST['ipban_ip'],$_POST['ipban_reason']));
		printf("<script type=\"text/javascript\">alert(\"%s / %s IP밴 %s\");</script>",
  			addslashes($_POST['ipban_username']),addslashes($_POST['ipban_ip']),($banresult)? "성공": "실패");
	}
?>
				<form name="ipbanfrm" action="admin.php?func=ipban" method="post">
					<table class="ban">
						<tbody>
							<tr>
								<th>대상 닉네임</th>
								<td><input id="ipban_username" name="ipban_username" type="text" /></td>
								<th>IP</th>
								<td><input id="ipban_ip" name="ipban_ip" type="text" /></td>
								<th>만료기한</th>
								<td><input id="ipban_until" name="ipban_until" type="text" /></td>
							</tr>
							<tr>
								<th>차단사유</th>
								<td colspan="5"><input id="ipban_reason" name="ipban_reason" type="text" /></td>
							</tr>
							<tr>
								<td colspan="6"><button class="ban_submit">추가</button></td>
							</tr>
						</tbody>
					</table>
				</form>
				<p class="summary">권한3 이상</p>
				<p class="summary">만료기한은 <u>무기한</u> 혹은 <u>년-월-일 시:분:초</u>(ex.2009-01-12 21:15:00) 형식</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>IP밴 조회/해제</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],5)) {
?>
				<iframe id="iframe_ipunban_list" src="adminparsing.php?func=ipunban&style=true"></iframe>
				<p class="summary">권한5 이상</p>
				<p class="summary">조회 시 LIKE 사용(대체문자 %)</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>킬 로그 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],3)) {
?>
                <table class="simpleform">
                    <tbody>
                        <tr>
                            <td style="border-bottom: none;">
                                <label for="killlog_inp_user" class="normallbl">피해자 닉네임 혹은 유저코드</label>
                                <input id="killlog_inp_user" class="killlog_inp" type="text" />
                            </td>
                            <td style="border-bottom: none;">
                                <button id="killlog_user_sub">조회</button>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <label for="killlog_inp_killer" class="normallbl">가해자 닉네임 혹은 유저코드</label>
                                <input id="killlog_inp_killer" class="killlog_inp" type="text" />
                            </td>
                            <td>
                                <button id="killlog_killer_sub">조회</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
				<div id="killlog" style="text-align: center;">
				</div>
				<p class="summary">권한3 이상</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>차량 파괴 로그 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],3)) {
?>
				<table class="simpleform">
					<tbody>
						<tr>
							<td style="border-bottom: none;">
								<label for="carblowlog_inp_vehicle" class="normallbl">차량 번호(LA XXXX) 혹은 DB ID</label>
								<input id="carblowlog_inp_vehicle" class="carblowlog_inp" name="carblowlog_vehicle" type="text" />
							</td>
							<td style="border-bottom: none;">
								<button id="carblowlog_vehicle_sub">조회</button>
							</td>
						</tr>
						<tr>
							<td>
								<label for="carblowlog_inp_user" class="normallbl">닉네임 혹은 유저코드</label>
								<input id="carblowlog_inp_user" class="carblowlog_inp" name="carblowlog_user" type="text" />
							</td>
							<td>
								<button id="carblowlog_user_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="carblowlog" style="text-align: center;"></div>
				<p class="summary">권한3 이상</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>접속 로그 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],3)) {
?>
				<table class="simpleform">
					<tbody>
						<tr>
							<td>
								<label for="connectiplog_inp_dest" class="connectiplog_lbl normallbl">닉네임 혹은 IP</label>
								<input id="connectiplog_inp_dest" class="connectiplog_inp" name="connectiplog_dest" type="text" />
							</td>
							<td>
								<button id="connectiplog_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="connectiplog" style="text-align: center;"></div>
				<p class="summary">권한3 이상</p>
				<p class="summary">조회 시 LIKE 사용(대체문자 %)</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>개명 로그 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],3)) {
?>
				<table class="simpleform">
					<tbody>
						<tr>
							<td>
								<label for="changenamelog_inp_dest" class="changenamelog_lbl normallbl">범주:키워드</label>
								<input id="changenamelog_inp_dest" class="changenamelog_inp" name="changenamelog_dest" type="text" />
							</td>
							<td>
								<button id="changenamelog_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="changenamelog" style="text-align: center;"></div>
				<p class="summary">권한3 이상</p>
				<p class="summary">조회 시 LIKE 사용(대체문자 %)</p>
				<p class="summary">범주: User, Admin 혹은 생략</p>
<?php
}
?>
			</div>
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>계정 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],6)) {
?>
				<table class="simpleform">
					<tbody>
						<tr>
							<td>
								<label for="checkuserdata_inp_dest" class="checkuserdata_lbl normallbl">닉네임 혹은 유저코드 혹은 Field:Value</label>
								<input id="checkuserdata_inp_dest" class="checkuserdata_inp" name="checkuserdata_dest" type="text" />
							</td>
							<td>
								<button id="checkuserdata_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="checkuserdata" style="text-align: center;"></div>
				<p class="summary">권한6 이상</p>
				<p class="summary">조회 시 LIKE 사용(대체문자 %)</p>
<?php
}
?>
			</div>
			
			<!--<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>데이터베이스 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if($UserData['ID'] == ACCOUNT_ACU || $UserData['ID'] == ACCOUNT_WLABYR) {
?>
				<table class="simpleform">
					<tbody>
						<tr>
							<td>
								<label for="databaseview_inp_sql" class="databaseview_lbl normallbl">SQL문</label>
								<input id="databaseview_inp_sql" class="databaseview_inp" name="databaseview_sql" type="text" />
							</td>
							<td>
								<button id="databaseview_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="databaseview" style="text-align: center;"></div>
				<p class="summary">Acu, 서성범 전용</p>
<?php
}
else {
?>
				<center>이 키트는 Acu, 서성범만 접근 가능합니다.</center>
<?php
}
?>
			</div>-->
			
			<!--<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>계정 삭제</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],6)) {
?>
				<table class="simpleform">
					<tbody>
						<tr>
							<td>
								<label for="deleteaccount_inp_dest" class="deleteaccount_lbl normallbl">닉네임 혹은 유저코드</label>
								<input id="deleteaccount_inp_dest" class="deleteaccount_inp" name="deleteaccount_dest" type="text" />
							</td>
							<td>
								<button id="deleteaccount_sub">삭제</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="deleteaccount" style="text-align: center;"></div>
				<p class="summary">권한6 이상</p>
				<p class="summary" style="color:#FF5555;">삭제 시 복구 불가능</p>
<?php
}
?>
			</div>-->
			
			<!--<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>유저 로그 조회</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],6)) {
?>
				<form name="checkuserlogfrm" action="admin.php?func=checkuserlog" method="post">
					<table class="checkuserlog">
						<tbody>
							<tr>
								<th>ID</th>
								<th>Type</th>
								<th>UserID</th>
								<th>DestID</th>
								<th>Value_A</th>
								<th>Value_B</th>
							</tr>
							<tr>
								<th colspan="4">Contents</th>
								<th colspan="2">Time</th>
							</tr>
							<tr>
								<td><input id="checkuserlog_inp_id" class="checkuserlog_inp" name="checkuserlog_id" type="text" /></td>
								<td><input id="checkuserlog_inp_type" class="checkuserlog_inp" name="checkuserlog_type" type="text" /></td>
								<td><input id="checkuserlog_inp_userid" class="checkuserlog_inp" name="checkuserlog_userid" type="text" /></td>
								<td><input id="checkuserlog_inp_destid" class="checkuserlog_inp" name="checkuserlog_destid" type="text" /></td>
								<td><input id="checkuserlog_inp_value_a" class="checkuserlog_inp" name="checkuserlog_value_a" type="text" /></td>
								<td><input id="checkuserlog_inp_value_b" class="checkuserlog_inp" name="checkuserlog_value_b" type="text" /></td>
							</tr>
							<tr>
								<td colspan="4"><input id="checkuserlog_inp_contents" class="checkuserlog_inp" name="checkuserlog_contents" type="text" /></td>
								<td colspan="2"><input id="checkuserlog_inp_time" class="checkuserlog_inp" name="checkuserlog_time" type="text" /></td>
							</tr>
							<tr>
								<td>
									<label for="checkuserlog_inp_limit" class="normallbl">LIMIT</label>
									<input id="checkuserlog_inp_limit" class="checkuserlog_inp" name="checkuserlog_limit" type="text" />
								</td>
								<td colspan="5"><button class="checkuserlog_sub">조회</button></td>
							</tr>
						</tbody>
					</table>
				</form>
<?php
	if($_GET['func'] == "checkuserlog")
	{
		$userlogsql = "SELECT * FROM log_user WHERE TRUE";
		if(strlen($_POST['checkuserlog_id']) > 0)
			$userlogsql .= " AND ID=".intval($_POST['checkuserlog_id']);
		if(strlen($_POST['checkuserlog_type']) > 0)
			$userlogsql .= " AND Type=".intval($_POST['checkuserlog_type']);
		if(strlen($_POST['checkuserlog_userid']) > 0)
			$userlogsql .= " AND UserID=".intval($_POST['checkuserlog_userid']);
		if(strlen($_POST['checkuserlog_destid']) > 0)
			$userlogsql .= " AND DestID=".intval($_POST['checkuserlog_destid']);
		if(strlen($_POST['checkuserlog_value_a']) > 0)
			$userlogsql .= " AND ValueA=".intval($_POST['checkuserlog_value_a']);
		if(strlen($_POST['checkuserlog_value_b']) > 0)
			$userlogsql .= " AND ValueB=".intval($_POST['checkuserlog_value_b']);
		if(strlen($_POST['checkuserlog_contents']) > 0)
			$userlogsql .= " AND Contents LIKE \'".$LOG->real_escape_string(htmlspecialchars_decode($_POST['checkuserlog_contents']))."\'";
		if(strlen($_POST['checkuserlog_time']) > 0)
			$userlogsql .= " AND Time LIKE \'".$LOG->real_escape_string(htmlspecialchars_decode($_POST['checkuserlog_time']))."\'";
		$userlogsql .= " ORDER BY Time DESC LIMIT ".$LOG->real_escape_string(htmlspecialchars_decode($_POST['checkuserlog_limit']));
		$userlogresult = $LOG->query($userlogsql);
		InsertLog($UserData,"Admin","유저 로그 조회(".$userlogsql.")");
		if($userlogresult->num_rows > 0)
		{
?>
				<table class="userlog">
					<tbody id="userlog_list">
<?php
			for($i = 0; $userlogdata[$i] = $userlogresult->fetch_array; $i++)
			{
?>
						<tr>
							<td><?=$userlogdata[$i]['ID']?>&nbsp;</td>
							<td><?=$userlogdata[$i]['Type']?>&nbsp;</td>
							<td><?=$userlogdata[$i]['UserID']?>&nbsp;</td>
							<td><?=$userlogdata[$i]['DestID']?>&nbsp;</td>
							<td><?=$userlogdata[$i]['Value_A']?>&nbsp;</td>
							<td><?=$userlogdata[$i]['Value_B']?>&nbsp;</td>
						</tr>
						<tr>
							<td colspan="4"><?=$userlogdata[$i]['Contents']?>&nbsp;</td>
							<td colspan="2"><?=$userlogdata[$i]['Time']?>&nbsp;</td>
						</tr>
<?php
			}
?>
					</tbody>
				</table>
<?php
		}
	}
?>
				<p class="summary">조회 조건은 AND 연산자로 결합</p>
				<p class="summary">Contents,Type은 LIKE 사용(대체문자 %)</p>
<?php
}
?>
			</div>-->
			
			<div id="conbox" class="head" style="background-color: #DDD;">
				<h1>로그인 세션 변경</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
<?php
if(AccessAdminKit($UserData['Admin'],7)) {
	if($_GET['func'] == "loginsession") {
		$dest = $DB->real_escape_string(htmlspecialchars_decode($_POST['loginsession_dest']));
		$loginsessionresult = $DB->query("SELECT Password,Username FROM user_data WHERE Username='".$dest."'");
		if($loginsessiondata = $loginsessionresult->fetch_row()) {
			$_SESSION['password'] = $loginsessiondata[0];
			$_SESSION['username'] = $loginsessiondata[1];
			InsertLog($UserData,"Admin",sprintf("로그인 세션 변경: %s",$loginsessiondata[1]),true);
			printf("<script type=\"text/javascript\">alert(\"로그인 세션 변경: %s\");</script>",$loginsessiondata[1]);
		}
		else {
			InsertLog($UserData,"Admin",sprintf("로그인 세션 변경: %s",$dest),false);
			printf("<script type=\"text/javascript\">alert(\"로그인 세션 변경 실패: %s\");</script>",$dest);;
		}
	}
?>
				<form name="loginsessionfrm" action="admin.php?func=loginsession" method="post">
					<table class="loginsession">
						<tbody>
							<tr>
								<th>닉네임</th>
								<td><input id="loginsession_dest" class="loginsession_inp" name="loginsession_dest" type="text" /></td>
								<td><button class="loginsession_submit">변경</button></td>
							</tr>
						</tbody>
					</table>
				</form>
				<p class="summary">권한7 이상</p>
<?php
}
?>
			</div>
<?php
include "./footer.inc.php";
?>
		</div>
	</body>
</html>