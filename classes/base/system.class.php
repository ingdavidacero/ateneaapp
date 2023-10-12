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
}

?>