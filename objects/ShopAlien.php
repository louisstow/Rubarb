<?php
class ShopAlien extends ORM {
	public static $table = "shop_alien";
	public static $key = array("location", "alienID");
	
	public static $attr = array(
		"location" => STRING,
		"alienID" => INT,
		"price" => INT
	);
	
	public static function getAll($location) {
		$q = ORM::query("SELECT a.*, s.price FROM shop_alien s INNER JOIN species a ON s.alienID = a.speciesID WHERE s.location = ?", array($location));
		
		$data = array();
		while($row = $q->fetch(PDO:FETCH_ASSOC)) $data[] = $row;
		
		return $data;
	}
}
?>