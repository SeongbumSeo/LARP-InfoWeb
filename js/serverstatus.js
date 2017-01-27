var gamePlayers = 0;
var gameMaxPlayers = 0;
var gameMode = null;
var playerList = null;
var playerIdArr = new Array();
var playerNameArr = new Array();
var playerPingArr = new Array();

$(document).ready(function () {
	updateServerStatus();
	setInterval("updateServerStatus()", 2000);
});

function updateServerStatus() {
	$.ajax({
		type: "get",
		url: "functions/serverstatus.php",
		cache: false
	}).done(function(data) {
		gamePlayers = parseInt($(data).find('Players').text());
		gameMaxPlayers = parseInt($(data).find('MaxPlayers').text());
		gameMode = $(data).find('GameMode').text();

		playerList = "";
		for(var i = 0; i < gamePlayers; i++) {
			var player = $(data).find('Player' + i);

			playerIdArr[i] = parseInt(player.find('ID').text());
			playerNameArr[i] = player.find('Nickname').text();
			playerPingArr[i] = parseInt(player.find('Ping').text());
			playerList +=
				"<tr>" +
				"<td>" + playerIdArr[i] + "</td>" +
				"<td>" + playerNameArr[i] + "</td>" +
				"<td>" + playerPingArr[i] + "</td>" +
				"</tr>";
		}

		if(gameMode == "") {
			$('.serverstatus-loading').addClass('hide');
			$('.serverstatus-offline').removeClass('hide');
			$('.serverstatus-online').addClass('hide');
		} else if(gameMaxPlayers == 0) {
			$('.serverstatus-loading').removeClass('hide');
			$('.serverstatus-offline').addClass('hide');
			$('.serverstatus-online').addClass('hide');
		} else {
			$("td.gameplayers").html(gamePlayers + "/" + gameMaxPlayers);
			$("td.gamemode").html(gameMode);
			$("table.playerlist tbody").html(playerList);

			$('.serverstatus-loading').addClass('hide');
			$('.serverstatus-offline').addClass('hide');
			$('.serverstatus-online').removeClass('hide');
		}
	}).fail(function() {
		$('.serverstatus-loading').addClass('hide');
		$('.serverstatus-offline').removeClass('hide');
		$('.serverstatus-online').addClass('hide');
	});
}
