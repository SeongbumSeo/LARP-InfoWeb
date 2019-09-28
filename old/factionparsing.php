<?php
require("initial.inc.php");
if(!AccessAdminKit($UserData['Admin'],7,false)) {
	print("아직 사용하실 수 없습니다.");
	exit;
}
$fresult = $DB->query("SELECT * FROM faction_data WHERE ID = ".$UserData['Faction']." LIMIT 1");
$fdata = $fresult->fetch_array();
switch($_GET['func']) {
	case 'mdc_ident':
		if($fdata['Type'] == 1) {
			$dest = addslashes($_GET['dest']);
			
			$uquery = $DB->query("SELECT * FROM user_data WHERE Username LIKE '$dest'");
			$bquery = $DB->query("SELECT * FROM ban_data WHERE Username='$dest' AND Valid=1 AND Until>CURRENT_TIMESTAMP");
			if($uquery->num_rows < 1 || $bquery->num_rows > 0) {
				InsertLog($UserData,"Faction","MDC 신원 조회($dest) 실패");
				printf("<b>%s</b> 조회 실패",$dest);
				exit;
			}
			if($uquery->num_rows > 1) {
				printf("<b>%s</b> 키워드에 해당하는 이름이 %d개 있습니다.<br />",$dest,$uquery)->num_rows;
				print("다음 중 한 명을 다시 조회해 보십시오.<br /><br />");
				while($udata = $uquery->fetch_assoc())
					printf("%s<br />",$udata['Username']);
				print("<br />");
				exit;
			}
			$udata = $uquery->fetch_array();
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
			
			$cquery = $DB->query("SELECT * FROM car_data WHERE NumberPlate LIKE 'LA $dest'");
			if($cquery->num_rows < 1) {
				InsertLog($UserData,"Faction","MDC 차량 조회($dest) 실패");
				printf("<b>%s</b> 조회 실패",$dest);
				exit;
			}
			$cdata = $cquery->fetch_array();
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