<?php
load("Item, Inventory, ShopItem");
data("item");

//get details about the item
$item = I('Item')->get($item);
if(!$item) {
	error("Not available");
}

$shop = I("ShopItem")->get($me->location, $item->itemID);
if(!$shop) {
	error("Not available");
}

$diff = array(
	"water" => 0,
	"jungle" => 10,
	"gas" => 15,
	"ice" => 20,
	"fire" => 30,
	"rock" => 50,
	"gas" => 100
);

//increase cost by the diff
$cost = $item->cost + ($item->cost * ($diff[$me->location] / 100));

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