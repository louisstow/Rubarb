<?php
load("Friends, Player");

Player::makeOffline();
$data = array(
	"all" => Player::online($me->location),
	"friends" => Friends::getFriends(USER)
);
		
echo json_encode($data);
?>