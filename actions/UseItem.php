<?php
load("Item, Inventory, Alien");
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
$alien->update();

//log the use of the item
if($me->battleID || isset($battle)) {
	$aj = json_encode($alien);
	$item = $item->itemName;
	I("BattleLog")->create($battle->battleID, NOW(), "{a: 'item', i: '{$item}', w: {$aj}}");
}

//decrement the quantity
$inv->quantity--;
if($inv->quantity <= 0) {
	$inv->remove();
} else {
	$inv->update();
}

ok();
?>