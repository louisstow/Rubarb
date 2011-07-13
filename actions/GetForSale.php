<?php
load("ShopItem, ShopAlien");

$items = ShopItem::getAll($me->location);
$aliens = ShopAlien::getAll($me->location);

$data = array(
	"items" => $items,
	"aliens" => $aliens
);

echo json_encode($data);
?>