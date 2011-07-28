<?php
load("Friends, Player");

$data = array(
	"all" => Player::online($me->location),
	"friends" => Friends::getFriends(USER)
);
		
echo json_encode($data);
?>