<?php
load("Battle");

echo json_encode(Battle::getRequests(USER));
?>