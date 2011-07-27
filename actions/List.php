<?php
load("Friends, Player");

echo "{all: ";
echo I("Player")->getMany(array("location" => $me->location, "status" => "online"))
		->select("playerID,screenName,wins,loses,status,location")
		->toJSON();
echo ", friends: ";
echo json_encode(Friends::getFriends(USER));
echo "}";
?>