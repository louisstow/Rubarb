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
	
	/**
	* Return all the objects to the rightful owners
	* if trade went awry
	*/
	public static function giveBack($trade) {
		$player = $trade->playerID;
		$friend = $trade->friendID;
		
		//update all the ITEMS
		$q = ORM::query("SELECT * FROM trade_items WHERE tradeID = :trade AND (playerID = :player OR playerID = :friend)",
			array("trade" => $trade->tradeID, "player" => $player, "friend" => $friend));
			
		while($row = $q->fetch(PDO::FETCH_ASSOC)) {
			ORM::query("UPDATE inventory SET quantity = quantity + :q WHERE playerID = :player AND itemID = :item",
				array("q" => $row['quantity'], "player" => $row['playerID'], "item" => $row['itemID']));
		}
		
		//update all the ALIENS
		ORM::query("UPDATE aliens SET status = 'carried' 
				    WHERE alienID IN(SELECT alienID FROM trade_aliens WHERE (playerID = :player OR playerID = :friend) AND tradeID = :trade)",
					array("trade" => $trade->tradeID, "player" => $player, "friend" => $friend));
				
		//remove the Trade Aliens
		ORM::query("DELETE FROM trade_aliens WHERE (playerID = :player OR playerID = :friend) AND tradeID = :trade",
					array("trade" => $trade->tradeID, "player" => $player, "friend" => $friend));
		
		//remove the Trade Items
		ORM::query("DELETE FROM trade_items WHERE tradeID = :trade AND (playerID = :player OR playerID = :friend)",
					array("trade" => $trade->tradeID, "player" => $player, "friend" => $friend));
	}
	
	/**
	* Both parties accepted so trade everything on offer
	*/
	public static function give($trade) {
		$player = $trade->playerID;
		$friend = $trade->friendID;
		
		//insert the items into the other players inventory
		ORM::query("INSERT INTO inventory SELECT '{$friend}', t.itemID, t.quantity FROM trade_items t WHERE tradeID = :trade AND playerID = :id
						ON DUPLICATE KEY UPDATE inventory.quantity = inventory.quantity + t.quantity", 
						array("trade" => $trade->tradeID, "id" => $player));
		
		
		ORM::query("INSERT INTO inventory SELECT '{$player}', t.itemID, t.quantity FROM trade_items t WHERE tradeID = :trade AND playerID = :id
						ON DUPLICATE KEY UPDATE inventory.quantity = inventory.quantity + t.quantity", 
						array("trade" => $trade->tradeID, "id" => $friend));
		
		//update the aliens with a new owner		
		ORM::query("UPDATE aliens SET playerID = :fid, status = 'carried'
					WHERE alienID IN(SELECT alienID FROM trade_aliens WHERE tradeID = :trade AND playerID = :pid)",
						array("trade" => $trade->tradeID, "fid" => $friend, "pid" => $player));
						
		ORM::query("UPDATE aliens SET playerID = :fid, status = 'carried'
					WHERE alienID IN(SELECT alienID FROM trade_aliens WHERE tradeID = :trade AND playerID = :pid)",
						array("trade" => $trade->tradeID, "fid" => $player, "pid" => $friend));
						
		//remove the Trade Aliens
		ORM::query("DELETE FROM trade_aliens WHERE (playerID = :player OR playerID = :friend) AND tradeID = :trade",
					array("trade" => $trade->tradeID, "player" => $player, "friend" => $friend));
		
		//remove the Trade Items
		ORM::query("DELETE FROM trade_items WHERE tradeID = :trade AND (playerID = :player OR playerID = :friend)",
					array("trade" => $trade->tradeID, "player" => $player, "friend" => $friend));
	}
}
?>