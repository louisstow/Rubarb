<?php
load("Alien, Species, ShopAlien");
data("species");

$shop = I("ShopAlien")->get($me->location, $species);

if(!$shop) {
	error("Not sold here");
}

if($shop->price > $me->money) {
	error("Not enough money");
}

$species = I("Species")->get($species);

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
$alien->hp = 100;
$alien->status = 'stored';

$alien->update();

//charge the user
$me->money - $shop->price;
$me->update();

ok();
?>