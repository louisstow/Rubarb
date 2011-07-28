<?php
load("Alien, BattleRequest, BattleTemp, Battle, BattleTeam, BattlePVP, BattleSnapshot");
data("type, friend");

if($me->battleID && $type == "pvp") {
	error("Already in battle");
}

if($friend == USER) {
	error("Stop playing with yourself");
}

$alien = I("Alien")->get(Alien::getNext(USER));
$opp = I("Alien")->get(Alien::getNext($friend));

if(!$alien) {
	error("You have no fit Topians");
}

if(!$opp) {
	error("Opponent unable to play");
}

$battle = I("Battle")->create(D, $type, USER, USER, NOW(), 'NULL', $me->location);
I("BattlePVP")->create($battle->battleID, USER, $alien->alienID, NOW(), $friend, $opp->alienID, NOW());

if($type == "pvp") {
	//store the temp stats
	I("BattleTemp")->create($battle->battleID, $alien->alienID, $alien->attack, $alien->defense, $alien->speed);
	I("BattleTemp")->create($battle->battleID, $opp->alienID, $opp->attack, $opp->defense, $opp->speed);
} else if($type == "test") {
	//create a snapshot
	BattleSnapshot::setup($battle->battleID, USER, $friend);
}

if($type == "pvp") {
	$me->battleID = $battle->battleID;
	$me->update();
}

echo json_encode(array(
	"battle" => $battle,
	"p1" => $alien,
	"p2" => $opp
));
?>