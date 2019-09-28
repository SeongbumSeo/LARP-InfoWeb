<div id="conbox" style="background-color: #5F5F5F; color: #FFF; border-radius: 0;">
	<p>LA:RP Information Website</p>
</div>
		
<!--[if IE]>
	<div id="conbox" style="background-color: #FFF; color: #FF0000;">
		<p>권장하지 않는 브라우저입니다!</p>
		<span style="color: #000;">
			<p>본 페이지는 Chrome, Firefox, Safari 기준으로 작성되었습니다.</p>
		</span>
	</div>
<![endif]-->

<span id="debuging" style="color: #FFF; background-color: #000;"></span>

<?php
if(IsUserLoggedIn()) {
?>
<div id="serverstatusdata"></div>

<!--<div id="conbox" class="serveronline" style="background: none no-repeat 50% 50%; background-image: url('./images/labuildings.jpg'); background-size: cover;">
	<h1 id="gamestart"><a href="larp://<?=$UserData['Username']?>" style="text-shadow: 0 0 7px #FFF; color:#FFF;">런처α 실행</a></h1>
	<p class="summary" style="color:#FFF;">런처 다운로드는 알파 테스터에게만 제공하고 있습니다.</p>
</div>-->

<?php
}
?>