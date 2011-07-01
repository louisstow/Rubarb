<?php
/**
* Check if the user has an item in their
* inventory.
*/
function hasItem($item) {
	$q = query("SELECT quantity FROM inventory WHERE playerID = :user AND itemID = :item",
			array("user" => USER, "item" => $item));
			
	$data = $q->fetch();
	if(!numRows()) return false;
	return $data['quantity'] > 0;
}

/**
* Add an item to the inventory, or increase
* the quantity.
*/
function addItem($item, $quantity) {
	query("INSERT INTO inventory VALUES(:user, :item, :quantity) ON DUPLICATE KEY quantity += :quantity",
		array("user" => USER, "item" => $item, "quantity" => $quantity));
}

/**
* Apply an item onto an alien and remove
* from the inventory.
*/
function useItem($itemID, $alien) {
	$item = getItem($itemID) or hacking();
	$item['alien'] = $alien;
	
	//remove unneeded vars
	unset($item['itemName']);
	unset($item['itemID']);
	unset($item['itemDescr']);
	
	query("UPDATE aliens 
		   SET attack = attack + :attack, 
			   defense = defense + :defense,
			   speed = speed + :speed,
			   exp = exp + :exp,
			   hunger = hunger + :hunger
			   thirst = thirst + :thirst
			   hp = hp + :hp
			WHERE alienID = :alien", $item);
			
	query("UPDATE inventory SET quantity = quantity - 1 WHERE userID = :user AND itemID = :item",
		array("user" => USER, "item" => $itemID));
}

/**
* Return the details of an item as an array
*/
function getItem($item) {
	$q = query("SELECT * FROM items WHERE itemID = :item LIMIT 1", 
		array("item" => $item));
		
	$data = $q->fetch(PDO::FETCH_ASSOC);
	if(!numRows()) return false;
	
	return $data;
}
?>