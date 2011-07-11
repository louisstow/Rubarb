<?php
load("Message");

$messages = I("Message")->join(array("fromID" => "players.playerID"), array("toID" => USER));

echo $messages->select("messageID, message, playerID, screenName, sentDate")->toJSON();

$ids = array();
foreach($messages as $message) {
	$ids[] = $message['messageID'];
}

$list = implode(',', $ids);
ORM::query("DELETE FROM messages WHERE messageID IN({$list})");
?>