<?php
//loop over 1 .. 10 and update the order of aliens
for($i = 1; $i <= 10; $i++) {
	if(isset($_GET[$i])) {
		ORM::query("UPDATE aliens SET alienOrder = ? WHERE alienID = ? AND playerID = ? AND status IN('carried', 'fainted')",
			array($i, $_GET[$i], USER));
	}
}

ok();
?>