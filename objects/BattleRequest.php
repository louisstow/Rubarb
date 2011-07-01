<?php
class Request extends ORM {
	public static $table = "requests";
	public static $key = array("battleID", "playerID");
	
	public static $attr = array(
		"battleID" => INT,
		"playerID" => INT,
		"requestDate" => DATE
	);
}
?>