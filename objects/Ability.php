<?php
class Ability extends ORM {
	public static $table = "ability";
	public static $key = array("speciesID", "moveID");
	
	public static $attr = array(
		"speciesID" => INT,
		"moveID" => STRING,
		"levelAquired" => INT
	);
	
	public static function getAttacks($alien) {
		$q = ORM::query("SELECT m.* FROM aliens al INNER JOIN ability a ON al.species = a.speciesID INNER JOIN moves m ON m.moveID = a.moveID
						 WHERE alienID = ? AND a.levelAquired <= al.level", array($alien));
						 
		$data = array();
		while($row = $q->fetch(PDO::FETCH_ASSOC)) $data[] = $row;
		
		return $data;
	}
}
?>