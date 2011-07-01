<pre>
<?php
load("Trade, TradeItem, TradeAlien");
data("trade, user");

$t = I("Trade")->get($trade);

//if no trade found and user is not participating
if(!$t || ($t->playerID != USER && $t->friendID != USER)) {
	error("No trade found");
}

//if listing what I offer
if($user == "me") {
	$user = USER;
} else {
	//whoever isn't me is the other person
	$user = ($t->playerID == USER) ? $t->friendID : $t->playerID;
}

$data = array();
$data['items'] = I("TradeItem")->join(array("itemID" => "items.itemID"), array("playerID" => $user))->result();
$data['aliens'] = I("TradeAlien")->join(array("alienID" => "aliens.alienID"), array("playerID" => $user))->result();

echo json_encode($data);
?>