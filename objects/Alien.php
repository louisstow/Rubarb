<?php
class Alien extends ORM {
	public static $table = "aliens";
	public static $key = array("alienID");
	
	public static $attr = array(
		"alienID" => INT,
		"alienAlias" => STRING,
		"playerID" => INT,
		"species" => INT,
		"attack" => INT,
		"defense" => INT,
		"speed" => INT,
		"exp" => INT,
		"level" => INT,
		"hp" => INT,
		"maxHP" => INT,
		"status" => STRING,
		"alienOrder" => INT
	);
	
	/**
	* Get the next alien available from a player. If none left,
	* then return FALSE.
	*/
	public static function getNext($user, $alien=-1) {
		//grab the first alien the user is carrying
		$q = ORM::query("SELECT alienID FROM aliens WHERE playerID = ? AND status = 'carried' AND alienID <> ? ORDER BY alienOrder LIMIT 1", 
			array($user, $alien));
		$data = $q->fetch(PDO::FETCH_ASSOC);
		
		if($data) {
			return $data['alienID'];
		} else return false;
	}
	
	public static function heal($user) {
		ORM::query("UPDATE aliens 
					SET hp = maxHP, status = 'carried'
					WHERE playerID = ? AND (status = 'carried' OR status = 'fainted')", array($user));
	}
}
?>