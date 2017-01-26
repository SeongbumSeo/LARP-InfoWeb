<?php
require_once("../classes/XmlConstruct.class.php");
require("../classes/SampQuery.class.php");
require("../config.php");

$query = new SampQuery(GAME_HOST, GAME_PORT); 
if(!$query->connect())
	die("closed");

$info = $query->getInfo();
$contents = array(
	'Players' => $info['players'],
	'MaxPlayers' => $info['maxplayers'],
	'GameMode' => $info['gamemode']
);

if($info['players'] < 100)
{
	$players = $query->getDetailedPlayers();
	$i = 0;
	foreach($players as $value)
		$contents['Player'.$i++] = array(
			'ID' => $value['playerid'],
			'Nickname' => $value['nickname'],
			'Ping' => $value['ping']
		);
}

$query->close();

$xml = new XmlConstruct('ServerStatus');
$xml->fromArray($contents);
$xml->output();
?>