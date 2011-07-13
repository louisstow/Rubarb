<?php
load("BattleTrain, Alien");
data("difficulty");

if($me->battleID) {
	error("In a battle");
}

$battle = new Battle();
$battle->type = "training";
$battle->ownerID = USER;
$battle->turn = USER;
$battle->startTime = NOW();
$battle->environment = $me->location;
$battle->status = "accepted";
$battle->accepted = 2;
$battle->needed = 2;
$battle->update();

$id = ORM::lastID();
$me->battleID = $id;
$me->update();

//get the players alien
$p = I("Alien")->get(Alien::getNext(USER));

//choose a species
$species = BattleTrain::choose($me->location);

$alien = new BattleTrain();
$alien->battleID = $id;
$alien->alienID = $p->alienID;
$alien->alianAlias = $species['speciesName'];
$alien->species = $species['speciesID'];
$alien->hp = 100;

if($difficulty == "1") {
	$alien->attack = $p->attack + rand(-1, 1);
	$alien->defense = $p->defense + rand(-1, 1);
	$alien->speed = $p->speed + rand(-1, 1);
	$alien->exp = $p->level * 10;
	$alien->level = $p->level;
} else if($difficulty == "2") {
	$alien->attack = $p->attack + 15 + rand(-5, 5);
	$alien->defense = $p->defense + 15 + rand(-5, 5);
	$alien->speed = $p->speed + 15 + rand(-5, 5);
	$alien->exp = ($p->level + 5) * 10 + rand(-5, 5);
	$alien->level = $p->level + 5;
} else if($difficulty == "3") {
	$alien->attack = $p->attack + 55 + rand(-10, 10);
	$alien->defense = $p->defense + 55 + rand(-10, 10);
	$alien->speed = $p->speed + 55 + rand(-10, 10);
	$alien->exp = ($p->level + 10) * 10 + rand(-10, 10);
	$alien->level = $p->level + 10;
} else {
	error("Incorrect details");
}

$alien->update();

ok();
?>