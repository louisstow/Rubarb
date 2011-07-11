<?php
load("Alien, Battle, BattlePVP, BattleSnapshot, BattleLog, Player");
data("battle, alien");

if(isset($battle)) {
	//if Test Match
	$battle = I("Battle")->get($battle);
} else {
	$battle = I("Battle")->get($me->battleID);
}

if(!$battle || $battle->turn != USER) {
	error("Not your turn");
}

$pvp = I("BattlePVP")->get($battle->battleID);
$title = (USER == $pvp->playerID) ? "player" : "opponent";

$a = I("Alien")->get($alien);

if(!$a || $a->playerID != USER) {
	error("Invalid alien");
}

$pvp->{$title . "Alien"} = $alien;

$aj = json_encode($a);
I("BattleLog")->create($battle->battleID, NOW(), "{a: 'switch', w: {$aj}}"

//alternate the turn
$battle->turn = (USER == $pvp->playerID) ? $pvp->opponentID : $pvp->playerID;
$battle->update();

//update last active
$pvp->{$title . "Active"} = NOW();
$pvp->update();

ok();
?>