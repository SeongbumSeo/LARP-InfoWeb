<?php
require_once("../classes/XmlConstruct.class.php");
require("../classes/SampQuery.class.php");
require("../config.php");

$query = new SampQuery(GAME_HOST, GAME_PORT); 
if(!$query->connect())
	die("Closed");

$info = $query->getInfo();
$contents = array(
	'Address' => GAME_HOST.":".GAME_PORT,
	'Players' => $info['players'],
	'MaxPlayers' => $info['maxplayers'],
	'GameMode' => $info['gamemode']
);

if($info['players'] < 100)
{
	$players = $query->getDetailedPlayers();
	$i = 0;
	foreach($players as $value)
		$contents['PlayerList'][$i++] = array(
			'ID' => $value['playerid'],
			'Nickname' => $value['nickname'],
			'Ping' => $value['ping']
		);
}

$query->close();

print(json_encode($contents));
?>