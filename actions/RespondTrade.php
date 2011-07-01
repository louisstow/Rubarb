<?php
load("Trade");
data("trade, response");

$trade = I("Trade")->get($trade);
if($trade->friendID == USER) {
	if($response == "A") {
		$trade->status = "Accepted";
		$trade->update();
	} else if($response == "D") {
		Trade::giveBack($trade);
		$trade->remove();
	}
	
	ok();
}

error("You were not requested");
?>