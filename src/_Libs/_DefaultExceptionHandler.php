<?php
if (!defined('_BASE_PATH')) {
	define('_BASE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
}
require_once(_BASE_PATH . '_lib/_Log.php');
require_once(_BASE_PATH . '_lib/_Util.php');

set_exception_handler('_DefaultExceptionHandler::handleException');

class _DefaultExceptionHandler{
	public static function handleException($e){
		_Log::fatal($e);
		_Redirect::_doRedirect('/500');
	}
}
?>