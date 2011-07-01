<?php
load("Request");


echo I("Request")
		->join(array("playerID"=>"players.playerID"), array("friendID" => USER))
		->select("playerID,screenName,wins,loses,status,location")
		->toJSON();
?>