<?php
class Trade extends ORM {
	public static $table = "trade";
	public static $key = array("tradeID");
	
	public static $attr = array(
		"tradeID" => INT,
		"playerID" => INT,
		"friendID" => INT,
		"tradeDate" => DATE
	);
}
?>