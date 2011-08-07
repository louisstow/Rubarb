<?php
class Player extends ORM {
	public static $table = "players";
	public static $key = array("playerID");
	
	public static $attr = array(
		"playerID" => INT,
		"screenName" => STRING,
		"email" => STRING,
		"playerPass" => PASSWORD,
		"wins" => INT,
		"loses" => INT,
		"money" => INT,
		"status" => STRING,
		"location" => STRING,
		"battleID" => INT,
		"lastActive" => DATE
	);
	
	public static function online($area) {
		$q = ORM::query("SELECT playerID, screenName, wins, loses FROM players WHERE status = 'online' AND playerID <> ? AND location = ?", 
			array(USER, $area));
			
		return ORM::fetchAll($q);
	}
	
	public static function makeOffline() {
		ORM::query("UPDATE players SET status = 'offline' WHERE DATE_ADD(lastActive, INTERVAL 1 HOUR) < ? AND status = 'online'", array(NOW()));
	}
}
?>