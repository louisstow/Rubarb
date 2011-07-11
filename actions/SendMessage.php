<?php
load("Message");
data("friend, message");

I("Message")->create(D, USER, $friend, $message, NOW());

ok();
?>