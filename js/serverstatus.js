var gamePlayers = 0;
var gameMode = null;
var playerList = null;
var playerIdArr = new Array();
var playerNameArr = new Array();
var playerPingArr = new Array();

$(document).ready(function () {
	updateServerStatus();
	setInterval("updateServerStatus()", 2500);
});

function updateServerStatus() {
	$.ajax({
		type: "get",
		url: "functions/serverstatus.php",
		cache: false
	}).done(function(data) {
		var statusdata = data.split('|');
		if(statusdata.length != 2) {
			gamePlayers = statusdata[0];
			gameMode = statusdata[1];
			playerList = "<tr><th>ID</th><th>닉네임</th><th>핑</th></tr>";
			for(var i = 2; i < statusdata.length; i++) {
				var playerdata = statusdata[i].split(',');
				playerIdArr[i] = playerdata[0];
				playerNameArr[i] = playerdata[1];
				playerPingArr[i] = playerdata[2];
				playerList += "<tr><td>" + playerIdArr[i] + "</td><td>" + playerNameArr[i] + "</td><td>" + playerPingArr[i] + "</td></tr>";
			}
		}
	});

	if(gamePlayers == "closed") {
		$('.serverstatus-loading').addClass('hide');
		$('.serverstatus-offline').removeClass('hide');
		$('.serverstatus-online').addClass('hide');
	} else if(!gameMode) {
		$('.serverstatus-loading').removeClass('hide');
		$('.serverstatus-offline').addClass('hide');
		$('.serverstatus-online').addClass('hide');
	} else {
		$("td.gameplayers").html(gamePlayers);
		$("td.gamemode").html(gameMode);
		$("table.playerlist").html(playerList);

		$('.serverstatus-loading').addClass('hide');
		$('.serverstatus-offline').addClass('hide');
		$('.serverstatus-online').removeClass('hide');
	}
}
