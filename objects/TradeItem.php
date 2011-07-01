<?php
class TradeItem extends ORM {
	public static $table = "trade_items";
	public static $key = array("tradeID", "playerID", "itemID");
	
	public static $attr = array(
		"tradeID" => INT,
		"playerID" => INT,
		"itemID" => INT,
		"quantity" => INT
	);
}
?>