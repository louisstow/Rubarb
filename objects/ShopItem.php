<?php
class ShopItem extends ORM {
	public static $table = "shop_item";
	public static $key = array("location", "itemID");
	
	public static $attr = array(
		"location" => STRING,
		"itemID" => INT
	);
	
	public static function getAll($location) {
		$q = ORM::query("SELECT i.* FROM shop_item s INNER JOIN item i ON s.itemID = i.itemID WHERE s.location = ?", array($location));
		
		$data = array();
		while($row = $q->fetch(PDO:FETCH_ASSOC)) $data[] = $row;
		
		return $data;
	}
}
?>