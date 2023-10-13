<?php
require_once "base.php";

class Category{
    private $table = 'categories';

    public function getAllCategories($token_str){
        //Metodo para traer toda la información de la tabla de categorias 

        //Validación del token recibido
        $token = new Token;
        $id_usuario = $token->getUserIdByToken($token_str);
        if(!$id_usuario){
            $response = new Respuesta;
            $data = $response->error_401('El token no es valido, o este ha caducado.');
            $response->createResponse($data);
            exit();
        }
        
        $connectiondb = Database::getInstance();
        $levels = $connectiondb->selectAsMatrix(
            [
                'id',
                'name'
            ],
            $this->table,
            [
                ['active',1]
            ]
        );
        return $levels;
    }
}

?>