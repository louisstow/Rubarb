<?php
load("Alien, Battle, BattlePVP, BattleSnapshot");
data("battle");

if(isset($battle)) {
	$battle = I("Battle")->get($battle);
} else {
	$battle = I("Battle")->get($me->battleID);
}

if(!$battle) {
	error("Battle doesn&apos;t exist");
}

$pvp = I("BattlePVP")->get($battle->battleID);

if($battle->type == "pvp") {
	$p1 = I("Alien")->get($pvp->playerAlien);
	$p2 = I("Alien")->get($pvp->opponentAlien);
} else if($battle->type == "test") {
	$p1 = I("BattleSnapshot")->get($battle->battleID, $pvp->playerAlien);
	$p2 = I("BattleSnapshot")->get($battle->battleID, $pvp->opponentAlien);
}

echo json_encode(array(
	"battle" => $battle,
	"p1" => $p1,
	"p2" => $p2
));
?>