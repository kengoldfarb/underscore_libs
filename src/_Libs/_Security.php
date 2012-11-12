<?php
class Security{
	public static function _requireSSL(){
		if(!self::_isSSL()){
			if(isset($_SERVER) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])){
				header("HTTP/1.1 301 Moved Permanently");
				header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
				exit();
			}else{
				_Log::fatal('$_SERVER variables are not set.  Unable to require SSL');
				throw new _Exception('$_SERVER variables are not set.  Unable to require SSL');
			}
		}
	}
	
	public static function _isSSL(){
		if (isset($_SERVER)) {
			// Check if SSL is enabled
			if(isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on"){
				return TRUE;
			}
		}
		return FALSE;
	}
}
?>