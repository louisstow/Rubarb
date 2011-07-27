<?php
load("Battle, BattleTrain, BattleTemp, Alien");
data("level");

if($me->battleID) {
	error("In a battle");
}

//get the players alien
$next = Alien::getNext(USER);
if(!$next) {
	error("You have no available aliens to train");
}

$battle = new Battle();
$battle->type = "training";
$battle->ownerID = USER;
$battle->turn = USER;
$battle->startTime = NOW();
$battle->environment = $me->location;
$battle->status = "accepted";
$battle->update();

$id = ORM::lastID();
$me->battleID = $id;
$me->update();

$p = I("Alien")->get($next);

//create the temporary stats
I("BattleTemp")->create($id, $next, $p->attack, $p->defense, $p->speed);

//choose a species
$species = BattleTrain::choose($me->location);

$alien = new BattleTrain();
$alien->battleID = $id;
$alien->alienID = $p->alienID;
$alien->alienAlias = $species['speciesName'];
$alien->species = $species['speciesID'];


if($level == "1") {
	//level minus 2
	$alien->level = $p->level - rand(3, 5);
	if($alien->level < 1) $alien->level = 1;
} else if($level == "2") {
	$alien->level = $p->level;
} else {
	$alien->level = $p->level + rand(3, 5);
}

$alien->maxHP = 3 * ($alien->level + 1) + 1;
$alien->hp = $alien->maxHP;
$alien->attack = floor($alien->level * 1.7);
$alien->defense = floor($alien->level * 1.5);
$alien->speed = floor($alien->level * 1.6);

$alien->update();

$data = array("battle" => $battle, "alien" => $p, "opp" => $alien);
echo json_encode($data);
?>