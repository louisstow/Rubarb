<?php
load("Alien, Battle, BattlePVP, BattleSnapshot, BattleLog, Player");
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

$pvp = I("BattlePVP")->get($battle->battleID);

//determine who's the Player and who is the Opponent
$title = (USER == $pvp->playerID) ? "player" : "opponent";
$opp = (USER == $pvp->playerID) ? "opponent" : "player";
$opponent = I("Player")->get($pvp->{$opp . "ID"});

//grab the instance of the aliens
if($battle->type == "pvp") {
	$p = I("Alien")->get($pvp->{$title . "Alien"});
	$o = I("Alien")->get($pvp->{$opp . "Alien"});
} else if($battle->type == "test") {
	$p = I("BattleSnapshot")->get($battle->battleID, $pvp->{$title . "Alien"});
	$o = I("BattleSnapshot")->get($battle->battleID, $pvp->{$opp . "Alien"});
}

//calculate the differences in stats
$adiff = ($p->attack - $o->attack) < 0 ? 0 : $p->attack - $o->attack;
$ddiff = ($p->defense - $o->defense) < 0 ? 0 : $p->defense - $o->defense;
$sdiff = ($p->speed - $o->speed) < 0 ? 0 : $p->speed - $o->speed;

$opponent->money += ceil(($adiff + $ddiff + $sdiff) * 5 + 100);
$opponent->wins++;
$o->attack += ceil(($adiff * 0.5) + 10);
$o->defense += ceil(($ddiff * 0.5) + 10);
$o->speed += ceil(($sdiff * 0.5) + 10);

//increase level based on the average of the stats
$level = ceil(($o->attack + $o->defense + $o->speed) / 3 * 0.1);
if($level > $o->level) {
	$o->level = $level;
}

$o->exp += $level * 5;

//update the loses for the opponent
$me->loses++;
$me->update();
$o->update();

$oj = json_encode($o);
I("BattleLog")->create($battle->battleID, NOW(), "{a: 'forfeit', w: {$me->playerID}, o: {$oj}}");

$battle->remove();

ok();
?>