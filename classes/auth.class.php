<?php

require_once "base.php";

class Auth{
    public function login($json){
        //Metodo para login en el sistema
        $response = new Respuesta;
        $data_input = json_decode($json,true);
        if(!isset($data_input['username']) || !isset($data_input['password'])){
            //Devuelve error debido a que estos dos campos son obligatorios
            return $response->error_400();
        }

        //Contrastar la información con la base de datos
        $username = $data_input['username'];
        $user = $this->getDataUser($username);
        if(!$user){
            return $response->error_200('Revisar datos enviados.');
        }

        $password = self::encrypt($data_input['password']);
        //Verificar la contraseña
        if($password != $user['password']){
            return $response->error_200('Revisar datos enviados.');
        }

        //Verificar el estado del usuario
        if($user['estado']!=1){
            return $response->error_200('El usuario está inactivo.');
        }
        
        //Creacion del token
        $token = $this->createToken($user['id']);
        if(!$token){
            return $response->error_500('Error interno, no se ha podido guardar el token.');
        }
        $result = $response->response;
        $result['result'] = [
            'token' => $token
        ];
        return $result;
    }

    private function getDataUser($username){
        //Método para traer la información
        $connectiondb = Database::getInstance();
        $data = $connectiondb->selectRowAsMap(
            [
                'id',
                'name nombre',
                'username',
                'password',
                'active estado'
            ],
            'users',
            [
                ['username',$username]
            ]
        );
        return $data;
    }

    protected function encrypt($string){
        //Medodo para encriptar con MD5
        return md5($string);
    }

    private function createToken($usuario_id){
        //Método para crear el token
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        
        $connectiondb = Database::getInstance();

        $connectiondb->update(
            'users_token',
            [
                ['token_states_id',2],
                ['updated_at',$date]
            ],
            [
                ['users_id',$usuario_id],
                'AND',
                ['token_states_id',1]
            ]
        );
        
        $id_token = $connectiondb->insert(
            'users_token',
            [
                ['users_id',$usuario_id],
                ['token',$token],
                ['created_at',$date],
                ['token_states_id',1]
            ]
        );

        if($id_token){
            return $token;
        }

        return 0;
    }
}

?>