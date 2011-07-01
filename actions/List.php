<?php
load("Friends, Player");
data("area");

if(isset($area)) {
	echo I("Player")->getMany(array("location" => $area, "status" => "online"))
			->select("playerID,screenName,wins,loses,status,location")
			->toJSON();
} else {
	echo json_encode(Friends::getFriends(USER));
}
?>