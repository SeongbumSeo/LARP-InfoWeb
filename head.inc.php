<?php
if(IsUserLoggedIn())
	InsertLog($UserData,"Navigated",$_SERVER['PHP_SELF']);
?>
<meta charset="utf-8" />
<meta name="viewport" content="user-scalable=yes,initial-scale=0.5,width=device-width" />
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<link rel="stylesheet" type="text/css" href="./style.css" />
<link rel="shortcut icon" type="image/x-icon" href="./favicon.ico" />
<!--[if IE]>
	<style type="text/css">
		h2 {
			color: #000;
			text-shadow: none;
		}
	</style>
<![endif]-->
<script src="./js/jquery-1.11.1.min.js"></script>
<script type="text/javascript">
	var gamePlayers = 0;
	var gameMode = null;
	var playerList = null;
	var playerIdArr = new Array();
	var playerNameArr = new Array();
	var playerPingArr = new Array();
	var serverStatus_FirstUpdate = false;
	var page = new Array();
	var pagesize = 30;
	
	$(window).on("load resize",function() {
		if($("body").height() < $(document).height())
			$("body").height($(document).height());
		//$("#debuging").html("width: " + $("body").prop("clientWidth") +" (" + ($("body").prop("clientWidth")/1920) + ")<br />height: " + $("body").prop("clientHeight") + " (" + ($("body").prop("clientHeight")/1050) + ")");
		if($("body").prop("clientWidth")/1920 < $("body").prop("clientHeight")/1050)
			$("html").css("background-size","auto 100%");
		else
			$("html").css("background-size","100% auto");
	});
	$(document).ready(function () {
		refreshTimeText();
		updateServerStatus();
		
		$(".smalltimestopped").each(function() {
			$(this).html(getTimeText());
		});

		$('input[class$=_inp]').on("focusin input focusout",function() {
			if($.trim($(this).val()))
				$(this).siblings("label").hide();
			else
				$(this).siblings("label").show();
		});

		$('button[class$=_submit]').click(function() {
			var rvalue = true;
			var cname = $(this).parents('form').attr('name').split('frm')[0];
			$('input[id^='+cname+'_]').each(function() {
				if(this.value.length < 1) {
					alert($(this).attr('id')+" 양식을 모두 입력하세요.");
					rvalue = false;
					return false;
				}
			});
			if(!rvalue || !confirm("계속하시겠습니까?"))
				return false;
			this.form.submit();
		});
		
		$('#idunban_list,#ipunban_list,#userlog_list').each(function() {
			var table = $(this);
			table.children().each(function() {
				var eClick = false;
				var eMouse = false;
				var rows = 1;
				switch(table.attr('id')) {
					case 'idunban_list':
						eClick = true;
						eMouse = true;
						rows = 3;
						break;
					case 'ipunban_list':
						eClick = true;
						eMouse = true;
						rows = 3;
						break;
					case 'userlog_list':
						eClick = false;
						eMouse = true;
						rows = 2;
						break;
				}

				var row = table.children().index(this);
				if(eClick) {
					$(this).click(function() {
						var brow;
						if(row % rows == rows - 1)
							return;
						else
							brow = row + rows - (row % rows) - 1;

						if(table.children('tr').eq(brow).css("display") != "table-row")
							table.children('tr').eq(brow).css("display","table-row");
						else
							table.children('tr').eq(brow).css("display","none");
						$('#iframe_'+table.attr('id'),parent.document).height(document.body.scrollHeight);
					});
				}
				if(eMouse) {
					$(this).mouseover(function() {
						for(var i = row - (row % rows); i < row - (row % rows) + rows; i++) {
								table.children('tr').eq(i).css("background-color","#AAA");
						}
					});
					$(this).mouseout(function() {
						for(var i = row - (row % rows); i < row - (row % rows) + rows; i++) {
							table.children('tr').eq(i).css("background-color","#FFF");
						}
					});
				}
			});
		});

		page = new Array(0,0);
		LoadUnbanTable();
	});
	function LoadUnbanTable() {
		var t = 0;
		$('#idunban_list,#ipunban_list').each(function() {
			var table = $(this);
			var p = 0;
			$('.unban_list_nav').eq(t).html("");
			table.children().each(function() {
				var row = table.children().index(this);
				if(row >= 3) {
					if((row-3)%pagesize == 0) {
						p++;
						var href = "javascript: page[" + t + "] = " + (p-1)+ "; LoadUnbanTable();";
						var nav = (page[t] == p-1) ? "<b style=\"color: #AA0000\">" + p + "</b>" : "<a href=\"" + href + "\">" + p + "</a>";
						$('.unban_list_nav').eq(t).html($('.unban_list_nav').eq(t).html() + "&nbsp;" + nav + "&nbsp;");
					}
					if(page[t]*pagesize > row-3 || (page[t]+1)*pagesize <= row-3) {
						table.children('tr').eq(row).css("display","none");
					}
					else if(row%3 == 0 || row%3 == 1) {
						table.children('tr').eq(row).css("display","table-row");
					}
				}
			});
			t++;
			$('#iframe_'+table.attr('id'),parent.document).height(document.body.scrollHeight);
		});
	}
	function refreshTimeText() {
		$(".smalltime").each(function() {
			$(this).html(getTimeText());
		});
		setTimeout("refreshTimeText()",1000);
	}
	function getTimeText() {
		var date = new Date();
		var hour = date.getHours();
		var text = date.getFullYear() + "-" + ("0"+(date.getMonth()+1)).slice(-2) + "-" + ("0"+date.getDate()).slice(-2);
		text += " " + ((hour > 12) ? "PM " + ("0"+(hour-12)).slice(-2) : "AM " + ("0"+hour).slice(-2));
		text += ":" + ("0"+date.getMinutes()).slice(-2) + ":" + ("0"+date.getSeconds()).slice(-2);
		return text;
	}
	function updateServerStatus() {
		$.ajax({
			type: "get",
			url: "serverstatus.php",
			cache: false,
			success: function(data) {
				$("#serverstatusdata").html(data);
				var statusdata = data.split('|');
				if(statusdata.length != 2) {
					gamePlayers = statusdata[0];
					gameMode = statusdata[1];
					playerList = "<tr><th>ID</th><th>Name</th><th>Ping</th></tr>";
					for(var i = 2; i < statusdata.length; i++) {
						var playerdata = statusdata[i].split(',');
						playerIdArr[i] = playerdata[0];
						playerNameArr[i] = playerdata[1];
						playerPingArr[i] = playerdata[2];
						playerList += "<tr><td>" + playerIdArr[i] + "</td><td>" + playerNameArr[i] + "</td><td>" + playerPingArr[i] + "</td></tr>";
					}
				}
			}
			
		});
		
		if(gamePlayers == "closed")
		{
			$(".loadingstatus").each(function()
			{
				$(this).hide();
			});
			$(".serveronline").each(function()
			{
				$(this).hide();
			});
			$(".serveroffline").each(function()
			{
				$(this).show();
			});
		}
		else if(!gameMode)
		{
			$(".loadingstatus").each(function()
			{
				$(this).show();
			});
			$(".serveronline").each(function()
			{
				$(this).hide();
			});
			$(".serveroffline").each(function()
			{
				$(this).hide();
			});
		}
		else
		{
			$("td#gameplayers").html(gamePlayers);
			$("td#gamemode").html(gameMode);
			$("table#playerlist").html(playerList);
			
			$(".loadingstatus").each(function()
			{
				$(this).hide();
			});
			$(".serveronline").each(function()
			{
				$(this).show();
			});
			$(".serveroffline").each(function()
			{
				$(this).hide();
			});
		}
		
		if(isPlayerOnline("<?=$UserData['Username']?>")) {
			$(".playeronline").each(function() {
				$(this).show();
			});
			$(".playeroffline").each(function() {
				$(this).hide();
			});
		}
		else {
			$(".playeronline").each(function() {
				$(this).hide();
			});
			$(".playeroffline").each(function() {
				$(this).show();
			});
		}
		
		if(!serverStatus_FirstUpdate) {
			serverStatus_FirstUpdate = true;
			setTimeout("updateServerStatus()",500);
		}
		else
			setTimeout("updateServerStatus()",2500);
	}
	function isPlayerOnline(playername) {
		for(var i = 0; i < playerNameArr.length; i++) {
			if(playerNameArr[i] == playername) {
				return true;
			}
		}
		return false;
	}
</script>