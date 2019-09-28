var gameAddress = "";
var gamePlayers = 0;
var gameMaxPlayers = 0;
var gameMode = "";
var playerList = "";

$(document).ready(function () {
	updateServerStatus();
});

function updateServerStatus() {
	gameAddress = "";
	gamePlayers = 0;
	gameMaxPlayers = 0;
	gameMode = "";
	playerList = "";

	$.ajax({
		type: "get",
		url: "functions/serverstatus.php",
		cache: false
	}).done(function(data) {
		if (parseInt(data) == 0)
			return;
		
		var json = JSON.parse(data);

		gameAddress = json.Address;
		gamePlayers = parseInt(json.Players);
		gameMaxPlayers = parseInt(json.MaxPlayers);
		gameMode = json.GameMode;

		if(json.PlayerList != undefined)
			for(var i = 0; i < json.PlayerList.length; i++) {
				playerList +=
					"<tr>" +
					"<td>" + parseInt(json.PlayerList[i].ID) + "</td>" +
					"<td>" + json.PlayerList[i].Nickname + "</td>" +
					"<td>" + parseInt(json.PlayerList[i].Ping) + "</td>" +
					"</tr>";
			}
	}).always(function(data) {
		if(data.readyState === 4) {
			$('.serverstatus-loading').addClass('hide');
			$('.serverstatus-error').removeClass('hide');
			$('.serverstatus-offline').addClass('hide');
			$('.serverstatus-online').addClass('hide');
		} else if(parseInt(data) == 0) {
			$('.serverstatus-loading').addClass('hide');
			$('.serverstatus-error').addClass('hide');
			$('.serverstatus-offline').removeClass('hide');
			$('.serverstatus-online').addClass('hide');
		} else if(gameAddress === "") {
			$('.serverstatus-loading').removeClass('hide');
			$('.serverstatus-error').addClass('hide');
			$('.serverstatus-offline').addClass('hide');
			$('.serverstatus-online').addClass('hide');
		} else {
			$('td.gameaddress').html(gameAddress);
			$('td.gameplayers').html(gamePlayers + "/" + gameMaxPlayers);
			$('td.gamemode').html(gameMode);
			$('table.playerlist tbody').html(playerList);

			$('.serverstatus-loading').addClass('hide');
			$('.serverstatus-error').addClass('hide');
			$('.serverstatus-offline').addClass('hide');
			$('.serverstatus-online').removeClass('hide');
		}

		setTimeout("updateServerStatus()", 5000);
	});
}
