<?php
class Energy extends ORM {
	public static $table = "energy";
	public static $key = array("alienID", "moveID");
	
	public static $attr = array(
		"alienID" => INT,
		"moveID" => INT,
		"amount" => INT
	);
	
	public static function init($alien) {
		ORM::query("INSERT INTO energy SELECT al.alienID, m.moveID, m.maxAmount 
				    FROM ability a INNER JOIN aliens al ON a.speciesID = al.species 
								   INNER JOIN moves m ON a.moveID = m.moveID WHERE al.alienID = ? 
								   AND m.moveID AND a.level < al.level NOT IN(SELECT e.moveID FROM energy e WHERE e.alienID = ?)",
				array($alien, $alien));
	}
	
	public static function restore($user) {
		ORM::query("UPDATE energy e INNER JOIN moves m ON e.moveID = m.moveID INNER JOIN aliens a ON a.alienID = e.alienID SET amount = m.maxAmount WHERE a.playerID = ?",
			array($user));
	}
}
?>