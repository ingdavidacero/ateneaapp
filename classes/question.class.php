<?php
require_once "base.php";

class Question{

    public function getQuestionById($id, $token_str){
        //Metodo para obtener la información de la pregunta a partir del id
        
        //Validación del token recibido
        $token = new Token;
        $id_usuario = $token->getUserIdByToken($token_str);
        if(!$id_usuario){
            $response = new Respuesta;
            $data = $response->error_401('El token no es valido, o este ha caducado.');
            $response->createResponse($data);
            exit();
        }

        //Consulta en base de datos
        $connectiondb = Database::getInstance();
        $data = $connectiondb->selectAsMatrix(
            [
                'q.id question_id',
                'q.description question_desc',
                'o.id option_id',
                'o.description option_desc'
            ],
            [
                'questions q',
                'JOIN options o ON q.id = o.questions_id'
            ],
            [
                [
                    'o.active',1
                ],
                'AND',
                [
                    'q.id',$id
                ]
            ]
        );
        
        //Agrupar las opciones en la pregunta consultada
        $data_result = [];
        foreach($data as $d){
            if (!array_key_exists('question_id', $data_result)) {
                $data_result['id'] = $d['question_id'];
                $data_result['description'] = $d['question_desc'];
            }
            $data_result['options'][]= [ 
                'id'=>$d['option_id'],
                'description'=>$d['option_desc']
            ];
        }
        return $data_result;
    }
}

?>