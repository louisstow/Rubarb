<?php
load("Inventory, Item");
data("item");

$inv = I("Inventory")->get(USER, $item);

//if the user doesn't own the item
if(!$inv || $inv->quantity < 1) {
	error("No item found");
}

$item = I("Item")->get($item);

$diff = array(
	"water" => 0,
	"jungle" => 10,
	"gas" => 15,
	"ice" => 20,
	"fire" => 30,
	"rock" => 50,
	"gas" => 100
);

$cost = ($item->cost / 2) + (($item->cost / 2) * ($diff[$me->location] / 100));

//add money
$me->money += $cost;
$me->update();

//decrement the quantity
$inv->quantity--;
if($inv->quantity <= 0) {
	$inv->remove();
} else {
	$inv->update();
}

ok();
?>