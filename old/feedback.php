<?php
include "./initial.inc.php";

$cid = intval($_GET['cid']);
switch($_GET['func'])
{
	case 'write':
		$sql = sprintf("INSERT INTO infoweb_feedback_data (Writer,IP,Contents,Writed) VALUES (%d,'%s','%s',1)",
	    	$UserData['ID'],$_SERVER['REMOTE_ADDR'],$DB->real_escape_string($_POST['result']));
		$DB->query($sql);
		header("location: ".LOCATION);
		break;
	case 'like':
		$query = $DB->query(sprintf("SELECT * FROM infoweb_feedback_data WHERE Writed = 1 AND ID = %d",$cid));
		if($FeedbackData = $query->fetch_assoc())
		{
			if($FeedbackData['Writer'] != $UserData['ID'] && strpos($FeedbackData[$i]['Likers'],sprintf("%d,",$UserData['ID'])) === false)
			{
				$DB->query(sprintf("UPDATE infoweb_feedback_data SET Likers = CONCAT(Likers,'%d,') WHERE ID = %d",
					$UserData['ID'],$cid));
			}
		}
		break;
	case 'delete':
			$query = $DB->query(sprintf("SELECT * FROM infoweb_feedback_data WHERE Writed = 1 AND ID = %d",$cid));
			if($FeedbackData = $query->fetch_assoc())
			{
				if($FeedbackData['Writer'] == $UserData['ID'] || AccessAdminKit($UserData['Admin'],7,false))
				{
					$DB->query(sprintf("UPDATE infoweb_feedback_data SET Writed = 0, DeletedBy = %d WHERE ID = %d",
						$UserData['ID'],$cid));
					InsertLog($UserData,"Admin",sprintf("인포웹 피드백 삭제: %d",$cid),true);
				}
			}
		break;
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
?>
		<title>Feedback - LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$(".navl_feedback").addClass("active");
			});
			
			function submitFeedback(obj)
			{
				var result = document.feedbackfrm.result;
				if(result.value.length < 1)
				{
					alert("내용을 입력하세요.");
					return false;
				}
				
				try { obj.form.submit(); }
				catch(e) { }
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
			<div id="conbox" class="head" style="background-color: #AADDDD;">
				<h1>피드백을 남겨 주세요!</h1>
			</div>
			<div id="conbox" class="body" style="background-color: #F0FFFF;">
				<center>
					<p>OBT 종료에 맞춰 <b style="color: #003333">계정 조회 시스템</b>으로 처음 소개되었던 인포웹이</p>
					<p>이제는 많은 분들이 이용해 주시는 편리한 웹앱이 되었습니다.</p>
					<p>앞으로는 어떤 모습이 될 것 같나요?</p>
					<p>여러분의 다양한 피드백을 남겨 주세요!</p>
				</center>
				<div style="height: 15px;">&nbsp;</div>
				<form name="feedbackfrm" action="feedback.php?func=write"  method="post">
					<textarea id="feedback_inp" name="result"></textarea>
					<button class="feedback_sub" onclick="return submitFeedback(this);">등록</button>
				</form>
<?php
$query = $DB->query("SELECT * FROM infoweb_feedback_data WHERE Writed = 1 ORDER BY Time DESC");
if($query->num_rows > 0)
{
?>
				<div style="height: 15px;">&nbsp;</div>
				<table class="contable feedback">
<?php
	for($i = 0; $FeedbackData[$i] = $query->fetch_array(); $i++)
	{
		$writer = $DB->query(sprintf("SELECT if(Admin > 0,AdminName,Username) FROM user_data WHERE ID = %d",
	    	$FeedbackData[$i]['Writer']))->fetch_row()[0];
?>
					<tr>
						<th><?=$writer?></th>
						<td><?=htmlspecialchars(stripslashes($FeedbackData[$i]['Contents']))?></td>
						<td><?=$FeedbackData[$i]['Time']?></td>
<?php
		$likes = substr_count($FeedbackData[$i]['Likers'],',');
	 	$liketext = ($likes)? sprintf("추천(%d)",$likes): "추천";
		if($FeedbackData[$i]['Writer'] != $UserData['ID'] && strpos($FeedbackData[$i]['Likers'],sprintf("%d,",$UserData['ID'])) === false)
		{
?>
						<td><a href="?func=like&cid=<?=$FeedbackData[$i]['ID']?>"><?=$liketext?></a></td>
<?php
		}
		else
		{
?>
						<td><font style="color: #999;"><?=$liketext?></font></td>
<?php
		}
		if($FeedbackData[$i]['Writer'] == $UserData['ID'] || AccessAdminKit($UserData['Admin'],7,false))
		{
?>
						<td><a href="?func=delete&cid=<?=$FeedbackData[$i]['ID']?>">삭제</a></td>
<?php
		}
		else
		{
?>
						<td><font style="color: #999;">삭제</font></td>
<?php
		}
?>
					</tr>
<?php
	}
?>
				</table>
<?PHP
}
else
{
?>
				<p>&nbsp;</p>
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