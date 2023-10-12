<?php

require_once "classes/auth.class.php";
require_once "classes/base/respuesta.class.php";

$auth = new Auth;
$response = new Respuesta;

if($_SERVER['REQUEST_METHOD'] == "POST"){
    //Recepcion de datos
    $postBody = file_get_contents("php://input");
    
    $data = $auth->login($postBody);

    $response->createResponse($data);
    exit();
}

header('Content-Type: application/json');
$data = $response->error_405();
$response->createResponse($data);


?>