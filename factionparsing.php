<?php
require("initial.inc.php");
if(!AccessAdminKit($UserData['Admin'],7,false)) {
	print("아직 사용하실 수 없습니다.");
	exit;
}
$fresult = mysql_query("SELECT * FROM faction_data WHERE ID = ".$UserData['Faction']." LIMIT 1",$DB);
$fdata = mysql_fetch_array($fresult);
switch($_GET['func']) {
	case 'mdc_ident':
		if($fdata['Type'] == 1) {
			$dest = addslashes($_GET['dest']);
			
			$uquery = mysql_query("SELECT * FROM user_data WHERE Username LIKE '$dest'",$DB);
			$bquery = mysql_query("SELECT * FROM ban_data WHERE Username='$dest' AND Valid=1 AND Until>CURRENT_TIMESTAMP",$DB);
			if(mysql_num_rows($uquery) < 1 || mysql_num_rows($bquery) > 0) {
				InsertLog($UserData,"Faction","MDC 신원 조회($dest) 실패");
				printf("<b>%s</b> 조회 실패",$dest);
				exit;
			}
			if(mysql_num_rows($uquery) > 1) {
				printf("<b>%s</b> 키워드에 해당하는 이름이 %d개 있습니다.<br />",$dest,mysql_num_rows($uquery));
				print("다음 중 한 명을 다시 조회해 보십시오.<br /><br />");
				while($udata = mysql_fetch_array($uquery))
					printf("%s<br />",$udata['Username']);
				print("<br />");
				exit;
			}
			$udata = mysql_fetch_array($uquery);
			InsertLog($UserData,"Faction","MDC 신원 조회($dest) 성공");
			
			switch($udata['Origin'])
			{
				case 0: $uorigin = "미국"; break;
				case 1: $uorigin = "한국"; break;
				case 2: $uorigin = "이탈리아"; break;
				case 3: $uorigin = "일본"; break;
				case 4: $uorigin = "스페인"; break;
				case 5: $uorigin = "러시아"; break;
				case 6: $uorigin = "프랑스"; break;
				case 7: $uorigin = "중국"; break;
				case 8: $uorigin = "이라크"; break;
				case 9: $uorigin = "독일"; break;
				case 10: $uorigin = "영국"; break;
				default: $uorigin = "알수없음"; break;
			}
?>
<table class="mdc_ident">
	<tr>
		<td rowspan="6" class="charimg" style="background-image: url('<?=GetSkinImage($udata['Skin'])?>');">&nbsp;</td>
		<th colspan="2"><?=str_replace('_',' ',$udata['Username'])?></th>
	</tr>
	<tr>
		<th>성별</th><td><?=($udata['Sex'] == 0) ? "남자" : "여자"?></td>
	</tr>
	<tr>
		<th>나이</th><td><?=$udata['Age']?></td>
	</tr>
	<tr>
		<th>전화번호</th><td><?=$udata['PhoneNumber']?></td>
	</tr>
	<tr>
		<th>국적</th><td><?=$uorigin?></td>
	</tr>
	<tr>
		<th>직업</th><td><?=GetJobName($udata['Job'])?></td>
	</tr>
</table>
<?php
		}
		break;
	case 'mdc_veh':
		if($fdata['Type'] == 1) {
			$dest = addslashes($_GET['dest']);
			
			$cquery = mysql_query("SELECT * FROM car_data WHERE NumberPlate LIKE 'LA $dest'",$DB);
			if(mysql_num_rows($cquery) < 1) {
				InsertLog($UserData,"Faction","MDC 차량 조회($dest) 실패");
				printf("<b>%s</b> 조회 실패",$dest);
				exit;
			}
			$cdata = mysql_fetch_array($cquery);
			InsertLog($UserData,"Faction","MDC 차량 조회($dest) 성공");
?>
<table class="mdc_veh">
	<tr>
		<td rowspan="3" class="carimg" style="background-image: url('<?=GetVehicleImage($cdata['Model'])?>');">&nbsp;</td>
		
	</tr>
</table>
<?php
		}
		break;
	default:
		printf("%s는 없는 기능입니다.",$_GET['func']);
		break;
}
?>