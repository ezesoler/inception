<?php

class InceptionPlugin_Log
{

	const PATH = __DIR__."/../";
	const FILE = "inc_debug.log";

	static function write($msg){
		if(DEBUG){
			$logfile = fopen(self::PATH.self::FILE,"a");
			fwrite($logfile,date("d-m-Y H:i:s").": ".$msg."\n");
	    	fclose($logfile);
    	}
	}

	static function get_log(){
		if(DEBUG){
			return file_get_contents(self::PATH.self::FILE);
		}
	}

	static function truncate_log(){
		if(DEBUG){
	    	$logfile = fopen(self::PATH.self::FILE,"a");
			ftruncate ( $logfile , filesize($logfile) );
	    	fclose($logfile);
    	}
	}

	static function get_location_log(){
		if(DEBUG){
	    	return realpath(self::PATH.self::FILE);
    	}
	}

}

?>