<?php
load("Trade");

echo I("Trade")
		->join(array("playerID" => "players.playerID"), array("friendID" => USER, "status" => 'Waiting'))
		->select("tradeID,playerID,screenName,wins,loses,status,location,tradeDate")
		->toJSON();
?>