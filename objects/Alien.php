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
		"hunger" => INT,
		"thirst" => INT,
		"hp" => INT,
		"status" => STRING
	);
}
?>