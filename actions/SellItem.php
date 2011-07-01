<?php
load("Inventory, Item");
data("item");

$inv = I("Inventory")->get(USER, $item);

//if the user doesn't own the item
if(!$inv || $inv->quantity < 1) {
	error("No item found");
}

$item = I("Item")->get($item);

//add money
$me->money += $item->cost / 2;
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