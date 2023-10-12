<?php

require_once "base.php";
require_once "classes/level.class.php";

class MatchesAnswers{

    private $table = 'matches_answers';
    private $numberquestions = 5;
    private $max_questions = 15;

    public function createDefaultMatch($match_id){
        //Metoto para crear una partida default
        
        //Verificar antes si existe una partida iniciada
        $preguntas = $this->getNumberQuestionsMatchById($match_id);
        if($preguntas){
            $this->getError200('Ya tiene asignada preguntas la partida.');
        }

        //Traer todos los niveles
        $level = new Level;
        $levels_db = $level->getAllLevels();

        $questions = [];
        foreach($levels_db as $level_db){
            $questions = array_merge(
                $questions,
                $this->getRandomQuestionsByLevel($level_db['id'])
            );
        }
        
        //Inserción de las preguntas en el match
        $match_questions = $this->insertMatchAnswer($questions,$match_id);
        
        return true;
    }

    public function createCustomMatch($match_id, $level_id, $quantity){
        //Metoto para crear una partida personalizada
        
        //Verificar antes si existe una partida iniciada
        $preguntas = $this->getNumberQuestionsMatchById($match_id);
        if($preguntas){
            $this->getError200('Ya tiene asignada preguntas la partida.');
        }
        
        if(!is_int($quantity)){
            $this->getError200('La cantidad debe ser un número entero');
        }
        //Verificar que la cantidad no exceda el maximo de preguntas

        if($this->max_questions < $quantity){
            $this->getError200('No se puede exceder de '.$this->max_questions.' preguntas.');
        }
        $this->numberquestions = $quantity;

        //Verificar que exista el nivel digitado
        $level = new Level;
        $level_id_db = $level->getLevelById($level_id);

        if(!$level_id_db){
            $this->getError200('El nivel digitado no existe.');
        }

        $questions = $this->getRandomQuestionsByLevel($level_id_db);

        //Inserción de las preguntas en el match
        $match_questions = $this->insertMatchAnswer($questions,$match_id);

        return true;
    }

    private function insertMatchAnswer($questions, $match_id){
        $number_question = 1;
        $match_questions = [];
        foreach($questions as $question){
            $match_answer = $this->createMatchAnswer($number_question,$question['id'],$match_id);
            //Si no se crea la partida manda error interno
            if(!$match_answer){
                $this->error_500('Error interno, no se ha podido guardar la pregunta.');
            }
            $match_questions[] = $match_answer;
            $number_question++;
        }
        return $match_questions;
    }
    
    private function groupOptionsByQuestion($data){
        //Agrupar por pregunta todas las opciones
        $data_result = [];
        foreach($data as $d){
            if (!array_key_exists($d['question_id'], $data_result)) {
                $data_result[$d['question_id']] = [
                    'id'=>$d['question_id'],
                    'description'=>$d['question_desc']
                ];                
            }
            $data_result[$d['question_id']]['options'][]= [ 
                'id'=>$d['option_id'],
                'description'=>$d['option_desc']
            ];
        }
        return $data_result;
    }

    public function getQuestionsMatchByToken($token_str){
        //Validación del token recibido
        $token = new Token;
        $id_usuario = $token->getUserIdByToken($token_str);
        if(!$id_usuario){
            $response = new Respuesta;
            $data = $response->error_401('El token no es valido, o este ha caducado.');
            $response->createResponse($data);
            exit();
        }
        //Obtener las preguntas con respuestas
        $connectiondb = Database::getInstance();
        $data = $connectiondb->selectAsMatrix(
            [
                'q.id question_id',
                'q.description question_desc',
                'o.id option_id',
                'o.description option_desc'
            ],
            [
                'matches_answers ma',
                'JOIN questions q ON q.id = ma.questions_id',
                'JOIN options o ON o.questions_id = q.id',
                'JOIN matches m ON m.id = ma.matches_id',
                'JOIN users u ON u.id = m.users_id',
                'JOIN users_token ut ON ut.users_id = u.id'
            ],
            [
                ['ut.token',$token_str],
                'AND',
                ['ut.token_states_id', 1]
            ],
            'ORDER BY ma.number_question'
        );
        if(!$data){
            $data = $response->error_200('La partida no tiene preguntas.');
            $response->createResponse($data);
            exit();
        }

        $data_result = $this->groupOptionsByQuestion($data);

        return $data_result;
    }

    private function getNumberQuestionsMatchById($match_id){
        //Método para verificar status match
        $connectiondb = Database::getInstance();
        $number_questions = $connectiondb->selectCellAsString(
            'COUNT(*)',
            $this->table,
            [
                ['matches_id',$match_id]
            ]
        );
        return $number_questions;
    }

    private function getRandomQuestionsByLevel($id_level){
        //Metodo para obtener preguntas al azar mediante la dificultad

        //Consulta base de datos
        $connectiondb = Database::getInstance();
        $data = $connectiondb->selectAsMatrix(
            [
                'id'
            ],
            'questions',
            [
                [
                    'active',1
                ],
                'AND',
                [
                    'levels_id',$id_level
                ]
            ],
            'ORDER BY RAND() LIMIT '.$this->numberquestions
        );

        return $data;
    }

    private function createMatchAnswer($number_question,$questions_id,$match_id){
        $connectiondb = Database::getInstance();
        $data = $connectiondb->insert(
            $this->table,
            [
                ['number_question',$number_question],
                ['questions_id',$questions_id],
                ['matches_id',$match_id]
            ]
        );
        return $data;
    }

    private function getError200($description){
        $response = new Respuesta;
        $data = $response->error_200($description);
        $response->createResponse($data);
        exit();
    }
    
    private function getError500($description){
        $response = new Respuesta;
        $data = $response->error_500($description);
        $response->createResponse($data);
        exit();
    }
}

?>