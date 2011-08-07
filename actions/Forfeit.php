<?php
load("Alien, Battle, BattlePVP, BattleSnapshot, BattleLog, Player, Energy");
data("battle");

if(isset($battle)) {
	//if Test Match
	$battle = I("Battle")->get($battle);
} else {
	$battle = I("Battle")->get($me->battleID);
}

if(!$battle || $battle->turn != USER) {
	error("Not your turn");
}

if($battle->type == "training") {
	$battle->remove();
	ok();
}

$pvp = I("BattlePVP")->get($battle->battleID);

//determine who's the Player and who is the Opponent
$title = (USER == $pvp->playerID) ? "player" : "opponent";
$opp = (USER == $pvp->playerID) ? "opponent" : "player";
$opponent = I("Player")->get($pvp->{$opp . "ID"});

//grab the instance of the aliens
$p = I("Alien")->get($pvp->{$title . "Alien"});
$o = I("Alien")->get($pvp->{$opp . "Alien"});

$awards = Battle::award($o, $p);

//update the loses for the opponent
$me->loses++;
$opponent->wins++;

if($battle->type == "pvp") {
	$me->battleID = NULL;
	$opponent->battleID = NULL;
}

$opponent->update();
$me->update();


$log = array (
	"action" => "forfeit",
	"turn" => $me->playerID,
	"win" => $awards
);

I("BattleLog")->create($battle->battleID, NOW(), json_encode($log));

$battle->remove();

ok();
?>