<?php
require("classes/SampQuery-1.1/SampQuery.class.php");
require("defines.inc.php");

$query = new SampQuery(GAME_HOST,GAME_PORT); 
if(!$query->connect())
{
	echo "closed";
	exit;
}

$info = $query->getInfo();
//print_r($info);
printf("%d/%d|%s",$info['players'],$info['maxplayers'],$info['gamemode']);

if($info['players'] < 100)
{
	$players = $query->getDetailedPlayers();
	//print_r($players);
	foreach($players as $value)
		printf("|%d,%s,%d",$value['playerid'],$value['nickname'],$value['ping']);
}

$query->close();
?>