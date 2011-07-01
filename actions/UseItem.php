<?php
load("Item, Inventory, Alien");
data("item, alien");

$inv = I("Inventory")->get(USER, $item);

//if no entry or quantity of 0
if(!$inv || $inv->quantity < 1) {
	error("No item found");
}

$item = I("Item")->get($item) or error("No item found");
$alien = I("Alien")->get($alien);

if($alien->playerID != USER) {
	hacking();
}

$alien->attack += $item->attack;
$alien->defense += $item->defense;
$alien->speed += $item->speed;
$alien->exp += $item->exp;
$alien->hunger += $item->hunger;
$alien->thirst += $item->thirst;
$alien->hp += $item->hp;
$alien->update();

//decrement the quantity
$inv->quantity--;
if($inv->quantity <= 0) {
	$inv->remove();
} else {
	$inv->update();
}

ok();
?>