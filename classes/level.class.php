<?php
require_once "base.php";

class Level{
    private $table = 'levels';
    public function getLevelById($id){
        //Metodo para consultar nivel a partir del id
        $connectiondb = Database::getInstance();
        $id_level = $connectiondb->selectCellAsString(
            'id',
            $this->table,
            [
                ['id',$id]
            ]
        );
        return $id_level;
    }

    public function getAllLevels(){
        //Metodo para traer toda la información de la tabla de niveles 
        $connectiondb = Database::getInstance();
        $levels = $connectiondb->selectAsMatrix(
            [
                'id',
                'name'
            ],
            $this->table
        );
        return $levels;
    }
}

?>