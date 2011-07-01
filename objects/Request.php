<?php
class Request extends ORM {
	public static $table = "requests";
	public static $key = array("playerID", "friendID");
	
	public static $attr = array(
		"playerID" => INT,
		"friendID" => INT,
		"requestDate" => DATE
	);
}
?>