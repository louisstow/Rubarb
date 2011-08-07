<?php
load("Item, Inventory, Alien, Battle, BattleLog, BattleSnapshot, BattlePVP");
data("item, alien, battle");

$inv = I("Inventory")->get(USER, $item);

//if no entry or quantity of 0
if(!$inv || $inv->quantity < 1) {
	error("No item found");
}

$item = I("Item")->get($item) or error("No item found");
$alien = I("Alien")->get($alien);

if(!$alien || $alien->playerID != USER) {
	error("No alien found");
}

//if in a battle, ensure your turn
if($me->battleID || isset($battle)) {
	
	if(isset($battle)) {
		$battle = I("Battle")->get($battle); 
	} else if($me->battleID) {
		$battle = I("Battle")->get($me->battleID);
		//grab the alien snapshot instead
		$alien = I("BattleSnapshot")->get($battle, $alien->alienID);
	}
	
	if($battle->turn != USER) {
		error("Not your turn");
	}
}

$alien->attack += $item->attack;
$alien->defense += $item->defense;
$alien->speed += $item->speed;
$alien->exp += $item->exp;
$alien->hp += $item->hp;

//cap the health
if($alien->hp > $alien->maxHP) {
	$alien->hp = $alien->maxHP;
}

$alien->update();

$log = array(
	"action" => "item",
	"item" => $item,
	"turn" => $me->playerID,
	"alien" => $alien
);

//log the use of the item
if($me->battleID || isset($battle)) {
	I("BattleLog")->create($battle->battleID, NOW(), json_encode($log));
	$pvp = I("BattlePVP")->get($battle->battleID);
	
	//alternate the turn
	$battle->turn = (USER == $pvp->playerID) ? $pvp->opponentID : $pvp->playerID;
	$battle->update();

	//update last active
	$title = (USER == $pvp->playerID) ? "player" : "opponent";
	$pvp->{$title . "Active"} = NOW();
	$pvp->update();
}

//decrement the quantity
$inv->quantity--;
if($inv->quantity <= 0) {
	$inv->remove();
} else {
	$inv->update();
}

echo json_encode($log);
?>