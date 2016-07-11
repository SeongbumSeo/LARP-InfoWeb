<?php
include "./initial.inc.php";
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html lang="ko">
	<head>
<?php
include "./head.inc.php";
?>
		<title>New - LA:RP Information Website</title>
		<script type="text/javascript">
			$(document).ready(function()
			{
				$(".navl_new").addClass("active");
			});
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
			
			<!-- 여기에 컨텐츠를 입력해 주세요. -->
			
<?php
include "./footer.inc.php";
?>
		</div>
	</body>
</html>
<?php
mysql_close();
?>