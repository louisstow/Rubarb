<?php
class TradeAlien extends ORM {
	public static $table = "trade_aliens";
	public static $key = array("tradeID", "playerID", "alienID");
	
	public static $attr = array(
		"tradeID" => INT,
		"playerID" => INT,
		"alienID" => INT
	);
}
?>