<?php
load("Trade, TradeItem, TradeAlien");
data("trade, response");

$t = I("Trade")->get($trade);

if(!$t || ($t->playerID != USER && $t->friendID != USER)) {
	error("No trade found");
}

//if user accepts
if($response == "A") {
	if($t->playerID == USER) {
		$t->playerStatus = "Accept";
	} else {
		$t->friendStatus = "Accept";
	}
	$t->update();
} else if($response == "D") {
	//else remove the trade
	Trade::giveBack($t);
	$t->remove();
	ok();
}

//both players accept, trade
if($t->playerStatus == "Accept" && $t->friendStatus == "Accept") {
	Trade::give($t);
	$t->remove();
}

ok();
?>