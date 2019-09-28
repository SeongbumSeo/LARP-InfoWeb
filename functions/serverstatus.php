<?php
require_once("../classes/SampQuery.class.php");
require_once("../config.php");

$query = new SampQuery(GAME_HOST, GAME_PORT); 
if(!$query->connect())
	die("0");

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