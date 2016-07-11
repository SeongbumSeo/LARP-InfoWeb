<?php
require("./initial.inc.php");

// Notice
$parser = new HTMLParser("http://cafe.daum.net/_c21_/bbs_list?grpid=1GYY4&mgrpid=&fldid=F0AS");
$parser->setHTMLStack(explode("<!-- 공지기능 적용끝  -->", $parser->getHTMLStack())[1]);
for($i = 0; $i < MAX_NOTICE; $i++) {
    $line = $parser->getContents("tr", $i);
    $subject = $parser->getContentsFromPart($line, "td", 2);
    $subject = str_replace("href=\"", "href=\"http://cafe.daum.net", $subject);
    $subject = str_replace("<a ", "<a target=\"_blank\" ", $subject);
    $date = $parser->getContentsFromPart($line, "td", 3);
    $notice[$i] = array('subject' => $subject, 'date' => $date);
}

// Event
$parser = new HTMLParser("http://cafe.daum.net/_c21_/bbs_list?grpid=1GYY4&mgrpid=&fldid=FEBW");
$parser->setHTMLStack(explode("<!-- 공지기능 적용끝  -->", $parser->getHTMLStack())[1]);
for($i = 0; $i < MAX_NOTICE; $i++) {
    $line = $parser->getContents("tr", $i);
    $subject = $parser->getContentsFromPart($line, "td", 2);
    $subject = str_replace("href=\"", "href=\"http://cafe.daum.net", $subject);
    $subject = str_replace("<a ", "<a target=\"_blank\" ", $subject);
    $date = $parser->getContentsFromPart($line, "td", 4);
    $event[$i] = array('subject' => $subject, 'date' => $date);
}

// Feedback
$cid = intval($_GET['cid']);
switch($_GET['func'])
{
    case 'write':
        $sql = sprintf("INSERT INTO infoweb_feedback_data (Writer,IP,Contents,Writed) VALUES (%d,'%s','%s',1)",
            $UserData['ID'],$_SERVER['REMOTE_ADDR'],mysql_real_escape_string($_POST['result']));
        mysql_query($sql,$DB);
        header("location: ".HTTP_SELF);
        break;
    case 'like':
        $query = mysql_query(sprintf("SELECT * FROM infoweb_feedback_data WHERE Writed = 1 AND ID = %d",$cid),$DB);
        if($FeedbackData = mysql_fetch_array($query))
        {
            if($FeedbackData['Writer'] != $UserData['ID'] && strpos($FeedbackData[$i]['Likers'],sprintf("%d,",$UserData['ID'])) === false)
            {
                mysql_query(sprintf("UPDATE infoweb_feedback_data SET Likers = CONCAT(Likers,'%d,') WHERE ID = %d",
                    $UserData['ID'],$cid),$DB);
            }
        }
        break;
    case 'delete':
        $query = mysql_query(sprintf("SELECT * FROM infoweb_feedback_data WHERE Writed = 1 AND ID = %d",$cid),$DB);
        if($FeedbackData = mysql_fetch_array($query))
        {
            if($FeedbackData['Writer'] == $UserData['ID'] || AccessAdminKit($UserData['Admin'],7,false))
            {
                mysql_query(sprintf("UPDATE infoweb_feedback_data SET Writed = 0, DeletedBy = %d WHERE ID = %d",
                    $UserData['ID'],$cid),$DB);
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
require("./head.inc.php");
?>
		<title>LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function() {
				$(".navl_home").addClass("active");
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
require("./navbar.inc.php");
?>
		<div id="contents">
<?php
require("./header.inc.php");
?>
            <div id="conbox" class="head" style="background-color: #DDD;">
                <h1>공지사항</h1>
            </div>
            <div id="conbox" class="body" style="background-color: #FFF;">
                <table class="noticetable">
                    <tbody>
<?php
    for($i = 0; $i < MAX_NOTICE; $i++)
        if($notice[$i]['subject'] != "&nbsp;" && $notice[$i]['subject'] != "") {
?>
                        <tr>
                            <td><?=$notice[$i]['subject']?></td>
                            <td><?=$notice[$i]['date']?></td>
                        </tr>
<?php
        }
?>
                    </tbody>
                </table>
            </div>
            
            <div id="conbox" class="head" style="background-color: #DDD;">
                <h1>이벤트</h1>
            </div>
            <div id="conbox" class="body" style="background-color: #FFF;">
                <table class="noticetable">
                    <tbody>
<?php
    for($i = 0; $i < MAX_NOTICE; $i++)
        if($event[$i]['subject'] != "&nbsp;" && $event[$i]['subject'] != "") {
?>
                        <tr>
                            <td><?=$event[$i]['subject']?></td>
                            <td><?=$event[$i]['date']?></td>
                        </tr>
<?php
        }
?>
                    </tbody>
                </table>
            </div>

			<div id="conbox" class="head" style="background-color: #AADDDD;">
                <h1>피드백</h1>
            </div>
            <div id="conbox" class="body" style="background-color: #F0FFFF;">
                <center>
                    <p>OBT 종료에 맞춰 <b style="color: #003333">계정 조회 시스템</b>으로 처음 소개되었던 인포웹이</p>
                    <p>이제는 많은 분들이 이용하는 편리한 웹앱이 되었습니다.</p>
                    <p>앞으로는 어떤 모습이 될 것 같나요?</p>
                    <p>여러분의 다양한 피드백을 남겨 주세요!</p>
                </center>
                <div style="height: 15px;">&nbsp;</div>
                <form name="feedbackfrm" action="index.php?func=write"  method="post">
                    <textarea id="feedback_inp" name="result"></textarea>
                    <button class="feedback_sub" onclick="return submitFeedback(this);">등록</button>
                </form>
<?php
$query = mysql_query("SELECT * FROM infoweb_feedback_data WHERE Writed = 1 ORDER BY Time DESC",$DB);
if(mysql_num_rows($query) > 0)
{
?>
                <div style="height: 15px;">&nbsp;</div>
                <table class="contable feedback">
<?php
    for($i = 0; $FeedbackData[$i] = mysql_fetch_array($query); $i++)
    {
        $writer = mysql_result(mysql_query(sprintf("SELECT if(Admin > 0,AdminName,Username) FROM user_data WHERE ID = %d",
            $FeedbackData[$i]['Writer']),$DB),0);
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
require("./footer.inc.php");
?>
		</div>
	</body>
</html>
<?php
mysql_close();
?>