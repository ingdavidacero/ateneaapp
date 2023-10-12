<?php

require_once "classes/match.class.php";
require_once "classes/matches_answers.class.php";
require_once "classes/base/respuesta.class.php";

$match = new Match;
$match_answers = new MatchesAnswers;
$response = new Respuesta;

if($_SERVER['REQUEST_METHOD'] == 'GET'){
    $headers = getallheaders();
    if(!isset($headers['token'])){
        $data = $response->error_400();
        $response->createResponse($data);
        exit();
    }

    $token = $headers['token'];
    $data = $match_answers->getQuestionsMatchByToken($token);

    $response->createResponse($data);
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    //Colocamos en variable los datos enviados
    $postBody = file_get_contents("php://input");
    $data = $match->create($postBody);
    
    $response->createResponse($data);
    exit();
}

$data = $response->error_405();
$response->createResponse($data);

?>