<?php
load("Alien, Species");
data("alien");

$species = I("Species")->get($alien);
print_r($species);

//create new Alien
$alien = new Alien();
$alien->alienAlias = $species->speciesName;
$alien->playerID = USER;
$alien->species = $species->speciesID;
$alien->attack = $species->attack + rand(-2, 2);
$alien->defense = $species->defense + rand(-2, 2);
$alien->speed = $species->speed + rand(-2, 2);
$alien->exp = 100;
$alien->level = 1;
$alien->hunger = 0;
$alien->thirst = 0;
$alien->hp = 100;
$alien->status = 'carried';

$alien->update();
?>