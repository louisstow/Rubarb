<?php
load("Aliens, Species");
data("choice");

if($me->status !== "new") {
	error("You chose already");
}

if($choice == "1") {
	$s = 1;
} else if($choice == "2") {
	$s = 4;
} else if($choice == "3") {
	$s = 7;
}

$species = I("Species")->get($s);

$alien = new Alien();
$alien->alienAlias = $species->speciesName;
$alien->playerID = USER;
$alien->species = $species->speciesID;
$alien->attack = $species->attack;
$alien->defense = $species->defense;
$alien->speed = $species->speed;
$alien->exp = 100;
$alien->level = 1;
$alien->hp = 100;
$alien->status = 'carried';

$alien->update();
$me->status = "online";
$me->update();

echo json_encode($alien);
?>