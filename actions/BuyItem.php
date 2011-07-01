<?php
load("Item, Inventory");
data("item");

//get details about the item
$item = I('Item')->get($item);

//if user can't afford item, send error
if($me->money < $item->cost) {
	error("Not enough money");
} else {
	//or take away the cost
	$me->money -= $item->cost;
	$me->update();
}

$inventory = I("Inventory")->get(USER, $item);

//if no item
if(!$inventory) {
	I("Inventory")->create($item, USER, 1);
} else {
	$inventory->quantity++;
	$inventory->update();
}

//tell the client it was valid
ok();
?>