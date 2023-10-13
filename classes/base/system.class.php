<?php

class System{
    public static function arrayGetKey($array, $key, $default=null){
		if(isset($array[$key])){
			return $array[$key];
		}
		return $default;
	}

    public static function humanTime($microtime=true, $clean=false){
		return (
			date(($clean ? 'Ymd_His' : 'Y-m-d H:i:s')) .
			($microtime ? substr((string)microtime(), 1, 8) : '')
		);
	}

	public static function log($data, $humanTime=null){
		self::logToFile($data, Settings::$files['logs']['system'], $humanTime);
	}

	private static function logToFile($data, $file, $humanTime){
		//Metodo para crear logs
		$newLine = PHP_EOL;
		switch(gettype($data)){
			case 'integer':
			case 'double':
			case 'string':
				break;
			default:
				$data = var_export($data,true);
				$newLine = ["\r\n", "\r", "\n"];
		}
		$data = str_replace($newLine, PHP_EOL.'	', $data);
		$directoryExists = self::touchDirectory(Settings::$paths['logs']);
		if($directoryExists){
			$debugInformation = (
				(isset($humanTime) ? $humanTime : self::humanTime()) .
				PHP_EOL .
				'Log:' .
				PHP_EOL .
				'	' . $data .
				PHP_EOL .
				'--------------------' .
				PHP_EOL
			);
			file_put_contents(
				Settings::$paths['logs']. '/' .$file,
				$debugInformation,
				FILE_APPEND
			);
		}
	}
	public static function touchDirectory($directory, $permissions=0755, $recursive=true){
		//Metodo para crear directorios
		if(!is_dir($directory)){
			if(@mkdir($directory, $permissions, $recursive)){
				return true;
			}else{
				if($directory !== Settings::$paths['logs']){
					self::log('No se pudo crear el directorio "' .$directory. '"');
				}
				return false;
			}
		}
		return true;
	}
}

?>