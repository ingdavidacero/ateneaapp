<?php
require_once "base.php";

class Token{
    private $table = 'users_token';

    public function getUserIdByToken($token){
        //Método para obtener user por token
        $connectiondb = Database::getInstance();
        $user_id = $connectiondb->selectCellAsString(
            'users_id',
            $this->table,
            [
                ['token',$token],
                'AND',
                ['token_states_id',1]
            ]
        );
        return $user_id;
    }

    public function updateToken($date){
        $connectiondb = Database::getInstance();
        $data = $connectiondb->update(
            $this->table,
            [
                ['token_states_id',2],
                ['updated_at',$date]
            ],
            [
                ['created_at',$date,'<'],
                'AND',
                ['token_states_id',1]
            ]
        );
        return $data;
    }
}

?>