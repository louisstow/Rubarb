<?php
class Inventory extends ORM {
	public static $table = "inventory";
	public static $key = array("playerID", "itemID");
	
	public static $attr = array(
		"playerID" => INT,
		"itemID" => INT,
		"quantity" => INT
	);
	
	public static function getItems($user) {
		$q = ORM::query("SELECT v.quantity, i.* 
						 FROM inventory v INNER JOIN items i ON v.itemID = i.itemID 
						 WHERE v.playerID = ?",
			array($user));
		
		$data = array();
		while($row = $q->fetch(PDO::FETCH_ASSOC)) {
			$data[] = $row;
		}
		
		return $data;
	}
}
?>