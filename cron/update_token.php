<?php

require_once "../classes/token.class.php";

$token = new Token;
$date = date("Y-m-d H:i");
echo $token->updateToken($date);

?>