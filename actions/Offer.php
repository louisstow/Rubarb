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
	
	ok();
} else if(isset($item)) {
	$i = I("Inventory")->get(USER, $item);
	if(!$i || $i->quantity < $quantity) {
		error("Don't have the item");
	}
	
	I("TradeItem")->create($trade, USER, $item, $quantity);
	
	ok();
}

error("Invalid Request");
?>