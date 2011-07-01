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
		"location" => STRING
	);
}
?>