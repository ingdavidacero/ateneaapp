<?php
require_once "base.php";
require_once "matches_answers.class.php";

class Match{
    private $table = 'matches';
    public function create($json){
        $response = new Respuesta;
        $data_input = json_decode($json,true);

        if(!isset($data_input['token'])){
            $data = $response->error_401();
            $response->createResponse($data);
            exit();
        }

        if(isset($data_input['level']) && !isset($data_input['quantity'])){
            $data = $response->error_400();
            $response->createResponse($data);
            exit();
        }

        //Validación del token recibido
        $token = new Token;
        $token_str = $data_input['token'];
        $id_usuario = $token->getUserIdByToken($token_str);
        if(!$id_usuario){
            $data = $response->error_401('El token no es valido, o este ha caducado.');
            $response->createResponse($data);
            exit();
        }

        //Verificar antes si existe una partida iniciada
        $status = $this->getStatusMatchByUser($id_usuario);
        if($status){
            $data = $response->error_200('Ya existe una partida iniciada.');
            $response->createResponse($data);
            exit();
        }

        $date = date("Y-m-d H:i");
        //Creación de la partida
        $connectiondb = Database::getInstance();
        $connectiondb->startTransaction();
        $match_states_id = 1;
        $match_id = $connectiondb->insert(
            $this->table,
            [
                ['created_at',$date],
                ['match_states_id',$match_states_id],
                ['users_id',$id_usuario]
            ]
        );
        //Si no se crea la partida manda error interno
        if(!$match_id){
            return $response->error_500('Error interno, no se ha podido guardar la partida.');
        }

        //Creación de preguntas para la partida

        $matches_answer = new MatchesAnswers;
        //Creación de preguntas default
        if(!isset($data_input['level'])){
            $data = $matches_answer->createDefaultMatch($match_id);
        }else{
            $level = $data_input['level'];
            $quantity = $data_input['quantity'];
            $data = $matches_answer->createCustomMatch($match_id, $level, $quantity);
        }
        
        $connectiondb->commitTransaction();

        if(!$data){
            return $response->error_500('Error interno, no se ha podido guardar la partida.');
        }
        
        $result = $response->response;
        $result['result'] = [
            'mensaje' => 'La partida se creo de manera correcta.'
        ];
        
        return $result;
    }

    private function getStatusMatchByUser($user_id){
        //Método para verificar status match
        $connectiondb = Database::getInstance();
        $match_db = $connectiondb->selectCellAsString(
            'id',
            $this->table,
            [
                ['users_id',$user_id],
                'AND',
                ['match_states_id',1]
            ]
        );
        return $match_db;
    }
}

?>