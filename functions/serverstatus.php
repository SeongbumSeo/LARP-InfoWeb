<?php
require("../config.php");
require("../classes/SampQuery.class.php");

$query = new SampQuery(GAME_HOST, GAME_PORT); 
if(!$query->connect())
	die("closed");

$info = $query->getInfo();
printf("%d/%d|%s",$info['players'],$info['maxplayers'],$info['gamemode']);

if($info['players'] < 100)
{
	$players = $query->getDetailedPlayers();
	foreach($players as $value)
		printf("|%d,%s,%d",$value['playerid'],$value['nickname'],$value['ping']);
}

$query->close();
?>