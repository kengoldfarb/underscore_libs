<?php
if (!defined('_BASE_PATH')) {
	define('_BASE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
}
require_once(_BASE_PATH . '_lib/_Log.php');
require_once(_BASE_PATH . '_lib/_Exception.php');

class _Util{
	public static function _redirect($url){
		header('Location: ' . $url);
	}
	
	public static function _sendCacheHeaders($expire=5) {
		if (!is_numeric($expire) || $expire < 1 || $expire > 240) {
			$expire = 5;
			_Log::warn("Attempt to send cache headers with invalid expiration");
		}
		header("Cache-Control: public"); // HTTP/1.1
		header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60 * $expire) . " GMT");
		header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
	}
	
	public static function _sendP3PHeaders() {
		header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
	}
	
	public static function _sendNOCacheHeaders() {
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: no-store,no-cache, must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private", false);
	}
}
?>