<?php

require_once "classes/question.class.php";  
require_once "classes/base/respuesta.class.php";

$question = new Question;
$response = new Respuesta;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $headers = getallheaders();
    if(!isset($headers['token'])){
        $data = $response->error_400();
        $response->createResponse($data);
        exit();
    }
    $token = $headers['token'];
    if(isset($headers['id'])){
        $id=$headers['id'];
        $data = $question->getQuestionById($id,$token);
        $response->createResponse($data);
        exit();
    }
    $data = $response->error_200('Falta algún parametro de consulta.');  
    $response->createResponse($data);
    exit();
}

$data = $response->error_405();
$response->createResponse($data);

?>