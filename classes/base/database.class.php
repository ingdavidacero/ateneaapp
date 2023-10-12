<?php

class Database{
    static private $instance = null;
    private $conn;

    private function __construct()
    {
        try {
            $this->conn = @new mysqli(
				Settings::$database['host'],
				Settings::$database['user'],
				Settings::$database['password'],
				Settings::$database['name'],
				intval(Settings::$database['port'])
			);
			if($this->conn->connect_errno){
                self::logAndExit('Error al conectar con la base de datos', $sql, true);
			}
			$this->conn->query('SET NAMES utf8');
        } catch (Exception $e) {
            self::logAndExit('Fallo en la conexión:'.$e->getMessage(), $sql);
        }
    }

    public function __clone() {
        throw new Exception("No se puede clonar un singleton.");
    }

    public function getConnection()
    {
        return $this->conn;
    }
    
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Database();
        }

        return self::$instance;
    }

    private function logAndExit($externalMessage, $sql, $connect=false){
		$humanTime = System::humanTime();
		$error = '';
		if($connect){
			$error = self::getConnection()->connect_errno.' '.self::getConnection()->connect_error;
		}else{
			$error = self::getConnection()->errno.' '.self::getConnection()->error;
		}
		/*System::log(
			($sql?'Query: '.$sql.PHP_EOL:'') . 'Error: ' . $error,
			$humanTime
		);*/
		exit($humanTime. ' :: ' .$externalMessage);
	}

    public function cleanValues($values, $type=null){
		$open = '';
		$close = '';
		if(!is_array($values)){
			if($values === null){
				return 'NULL';
			}
			$values = [$values];
		}else{
			$open = '(';
			$close = ')';
		}
		foreach($values as $key=>$value){
			if($type === 'date'){
				$values[$key] = strtotime($value);
			}else{
				$values[$key] = self::getConnection()->real_escape_string($value);
			}
		}
		return $open . '"' . implode('","', $values) . '"' . $close;
	}
    
    private function conditionsToSql($conditions){
		$sql = '';
		foreach($conditions as $condition){
			$sql .= ' ';
			if(is_array($condition)){
				$operador = '=';
				if(is_array($condition[1])){
					$operador = 'IN';
				}elseif(isset($condition[2])){
					$operador = $condition[2];
				}
				$sql .=
					$condition[0] .
					' ' .
					$operador .
					' ' .
					self::cleanValues($condition[1])
				;
			}else{
				$sql .= $condition;
			}
		}
		return $sql;
	}
    
    public function select($fields, $tables, $conditions=null, $extra='',$subQueryAlias=''){
		if(!is_array($fields)){
			$fields = [$fields];
		}
		if(!is_array($tables)){
			$tables = [$tables];
		}
		$sql =
			'SELECT ' .
			implode(',', $fields) .
			' FROM ' .
			implode(' ', $tables)
		;
		if($conditions !== null){
			$sql .=
				' WHERE' .
				self::conditionsToSql($conditions)
			;
		}
		if($extra){
			$sql .= ' '.$extra;
		}
		if($subQueryAlias){
			return '('.$sql.') '.$subQueryAlias;
		}
		
		$result = self::getConnection()->query($sql);
		
		if($result === false){
			self::logAndExit('Error al seleccionar de la base de datos', $sql);
		}
		return $result;
	}
	
    public function selectAsMatrix($fields, $tables, $conditions=null, $extra=''){
		$result = self::select($fields, $tables, $conditions, $extra);
		$matrix = [];
		while($row = $result->fetch_assoc()){
			$matrix[] = $row;
		}
		return $matrix;
	}

	public function selectCellAsString($fields, $tables, $conditions=null, $extra=''){
		$row = self::selectRowAsArray($fields, $tables, $conditions, $extra);
		if(empty($row)){
			return '';
		}
		return $row[0];
	}

	public function selectRowAsArray($fields, $tables, $conditions=null, $extra=''){
		$result = self::select($fields, $tables, $conditions, $extra.' LIMIT 1');
		if($result->num_rows === 0){
			return [];
		}
		return $result->fetch_row();
	}

	public function selectRowAsMap($fields, $tables, $conditions=null, $extra=''){
		$result = self::select($fields, $tables, $conditions, $extra.' LIMIT 1');
		if($result->num_rows === 0){
			return [];
		}
		return $result->fetch_assoc();
	}

    public function insert($table, $fields){
		$names = [];
		$values = [];
		foreach($fields as $field){
			$names[] = $field[0];
			$values[] = (
				self::cleanValues($field[1], System::arrayGetKey($field,2))
			);
		}
		$sql =
			'INSERT INTO ' .
			$table .
			' (' . implode(',', $names) . ')' .
			' VALUES' .
			' (' . implode(',', $values) . ')'
		;
		$result = self::getConnection()->query($sql);
		if($result === false){
			self::logAndExit('Error al insertar en la base de datos', $sql);
		}
		return self::getConnection()->insert_id;
	}

	public function startTransaction(){
		$result = self::getConnection()->query('START TRANSACTION');
		if($result === false){
			self::logAndExit('Error al iniciar la transacción', $sql);
		}
		return $result;
	}

	public function commitTransaction(){
		$result = self::getConnection()->query('COMMIT');
		if($result === false){
			self::logAndExit('Error al hacer commit en la transacción', $sql);
		}
		return $result;
	}
	
	public function update($table, $fields, $conditions){
		$sql =
			'UPDATE ' .
			$table .
			' SET '
		;
		foreach($fields as $key=>$field){
			$fields[$key] = (
				$field[0] .
				'=' .
				self::cleanValues($field[1], System::arrayGetKey($field,2))
			);
		}
		$sql .=
			implode(',', $fields) .
			' WHERE' .
			self::conditionsToSql($conditions)
		;
		$result = self::getConnection()->query($sql);
		if($result === false){
			self::logAndExit('Error al actualizar la base de datos', $sql);
		}
		return $result;
	}
}

?>