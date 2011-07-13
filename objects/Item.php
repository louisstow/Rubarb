<?php
class Item extends ORM {
	public static $table = "items";
	public static $key = array("itemID");
	
	public static $attr = array(
		"itemID" => INT,
		"itemName" => STRING,
		"itemDescr" => STRING,
		"attack" => INT,
		"defense" => INT,
		"speed" => INT,
		"exp" => INT,
		"hp" => INT,
		"cost" => INT
	);
}
?>