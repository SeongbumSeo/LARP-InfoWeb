<?php
include "./initial.inc.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
$fresult = mysql_query("SELECT * FROM faction_data WHERE ID = ".$UserData['Faction']." LIMIT 1",$DB);
$fdata = mysql_fetch_array($fresult);
switch($fdata['Type']) {
	case 1:
		$ftype = "정부군";
		break;
	case 2:
		$ftype = "의료기관";
		break;
	case 3:
		$ftype = "범죄";
		break;
	case 4:
		$ftype = "방송기관";
		break;
	default:
		$ftype = "없음";
		break;
}
?>
		<title><?=$fdata['Name']?> - LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$(".navl_faction").addClass("active");
				$(".factionmark").height($(".factionmark").width());

				var factionMemberList_FirstUpdate = false;				

				updateFactionMemberList();
				
				$('button[id=mdc_ident_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "factionparsing.php?func=mdc_ident&dest=" + $('#mdc_ident_inp_dest').val(),
						cahce: false,
						headers: {
							"cache-control": "no-cache",
							"pragma": "no-cache"
						},
						success: function(data) {
							$("#mdc_ident_result").html(data);
						}

					});
				});
				$('button[id=mdc_veh_sub]').click(function() {
					$.ajax({
						type: "get",
						url: "factionparsing.php?func=mdc_veh&dest=" + $('#mdc_veh_inp_dest').val(),
						cahce: false,
						headers: {
							"cache-control": "no-cache",
							"pragma": "no-cache"
						},
						success: function(data) {
							$("#mdc_veh_result").html(data);
						}

					});
				});
			});
			function updateFactionMemberList() {
				if(!factionMemberList_FirstUpdate) {
					factionMemberList_FirstUpdate = true;
					setTimeout("updateFactionMemberList()",500);
				}
				else
					setTimeout("updateFactionMemberList()",2500);
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

if($UserData['Faction'] > 0) {
?>
			<div id="conbox" class="factioninfo">
				<h1><?=$fdata['Name']?></h1>
				<table class="factioninfotable">
					<tr>
						<td>
							<div class="factionmark" style="background-image: url('./factionmarks/<?=$UserData['Faction']?>.png');">
								&nbsp;
							</div>
						</td>
						<td>
							<table class="factiondetailtable">
								<tr>
									<th>명칭</th>
									<td><?=$fdata['Name']?></td>
								</tr>
								<tr>
									<th>유형</th>
									<td><?=$ftype?></td>
								</tr>
								<tr>
									<th>설립일</th>
									<td><?=date("Y년 m월 d일",strtotime($fdata['Time']))?></td>
								</tr>
								<tr>
									<th>리더</th>
									<td><?=str_replace('_',' ',$fdata['Leader'])?></td>
								</tr>
								<tr>
									<th>부리더</th>
									<td><?=str_replace(',','<br />',str_replace('_',' ',$fdata['SubLeaders']))?></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<!--<div class="factionintroduction">
					이곳에 팩션 소개를 입력해 주세요.
				</div>-->
			</div>

			<div id="conbox" class="head" style="background-color:#AADDAA;">
				<h1>팩션원 명단</h1>
			</div>
			<div id="conbox" class="body" style="background-color:#FFF;">
				
			</div>

<?php
	switch($fdata['Type']) {
		case 1:
?>
			<div id="conbox" class="head" style="background-color: #AADDAA;">
				<h1>MDC</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #FFF;">
				<table class="simpleform">
					<tbody>
						<tr>
							<th>
								신원 조회
							</th>
							<td>
								<label for="mdc_ident_inp_dest" class="mdc_ident_lbl normallbl">이름</label>
								<input id="mdc_ident_inp_dest" class="mdc_ident_inp" name="mdc_ident_dest" type="text" />
							</td>
							<td>
								<button id="mdc_ident_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="mdc_ident_result" style="text-align: center; margin: 1px 0;"></div>
				<table class="simpleform">
					<tbody>
						<tr>
							<th>
								차량 조회
							</th>
							<td>
								<label for="mdc_veh_inp_dest" class="mdc_ident_lbl normallbl">번호판 (XXXX)</label>
								<input id="mdc_veh_inp_dest" class="mdc_veh_inp" name="mdc_veh_dest" type="text" />
							</td>
							<td>
								<button id="mdc_veh_sub">조회</button>
							</td>
						</tr>
					</tbody>
				</table>
				<div id="mdc_veh_result" style="text-align: center; margin: 1px 0;"></div>
				<p class="summary">와일드카드 %(임의의 문자들) _(임의의 한 문자) 사용 가능</p>
			</div>
<?php
			break;
	}
?>
			<div id="conbox" style="background-image: url('./images/construction.jpg'); color: #FFF;">
				<h1 class="chest">공사중! 쿵쾅쿵쾅!!</h1>
				<div style="height: 200px;">&nbsp;</div>
				<p class="smalltime"></p>
			</div>
<?php
}
include "./footer.inc.php";
?>
		</div>
	</body>
</html>
<?php
mysql_close();
?>