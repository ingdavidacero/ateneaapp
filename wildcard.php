<?php

require_once "classes/wildcard.class.php";  
require_once "classes/base/respuesta.class.php";

$wildcard = new WildCard;
$response = new Respuesta;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $headers = getallheaders();
    if(!isset($headers['token'])){
        $data = $response->error_400();
        $response->createResponse($data);
        exit();
    }
    $token = $headers['token'];
    $data = $wildcard->getAllWildCards($token);

    $response->createResponse($data);
    exit();
}

$data = $response->error_405();
$response->createResponse($data);
exit();

?>