<?php
load("Trade, TradeItem, TradeAlien, Alien, Inventory");
data("trade, alien, item, quantity");

$tr = I("Trade")->get($trade);
if(!$tr) {
	error("Trade not found");
}

if(isset($alien)) {
	$a = I("Alien")->get($alien);
	if($a->playerID != USER) {
		error("Alien not yours");
	}
	
	//enter the trade
	I("TradeAlien")->create($trade, USER, $alien);
	
	//make sure the user can't use the alien
	$a->status = 'limbo';
	$a->update();
	
	ok();
} else if(isset($item)) {
	$i = I("Inventory")->get(USER, $item);
	$ti = I("TradeItem")->get($trade, USER, $item);
	
	if(!$i) {
		error("Don't have the item");
	}
	
	//calculate the real amount based on existing trade
	$realQuantity = ($ti) ? $ti->quantity + $i->quantity : $i->quantity;
	
	if($realQuantity < $quantity) {
		error("Don't have the item");
	}
	
	//if offer already exists, reset quantity
	if($ti) {
		$ti->quantity = $quantity;
		$ti->update();
	} else {
		I("TradeItem")->create($trade, USER, $item, $quantity);
	}
	
	//update the inventory
	$i->quantity = $realQuantity - $quantity;
	$i->update();
	
	ok();
}

error("Invalid Request");
?>