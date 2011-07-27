<?php
load("Aliens, Species");
data("choice");

if($me->status !== "new") {
	error("You chose already");
}

if($choice == "1") {
	$s = 7; //DOOTH
} else if($choice == "2") {
	$s = 1; //POSSEL
} else if($choice == "3") {
	$s = 4; //SKARRIER
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
$alien->level = 6;
$alien->hp = 22;
$alien->maxHP = 22;
$alien->status = 'carried';

$alien->update();
$me->status = "online";
$me->update();

echo json_encode($alien);
?>