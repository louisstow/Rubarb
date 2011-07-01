<?php
load("Inventory");

$items = Inventory::getItems(USER);

echo json_encode($items);
?>