<?php
require_once 'PHPUnit/Autoload.php';
require_once(realpath(dirname(__FILE__) . '/../') . '/' . '_lib/_Config.php');

if(!defined('_TEST_ENV')){
	define('_TEST_ENV', 'local');
}

_Config::init(_TEST_ENV);
require_once(_LIB . '_Log.php');

class _LogTest extends PHPUnit_Framework_TestCase{
	public function testLoggingWithEcho(){
		_Log::$logEcho = true;
		_Log::$logLevel = _DEBUG;
		self::doDebugLog('test log 1', $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_DEBUG] \[.*\] test log 1/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		self::doWarnLog('test log 2', $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_WARN] \[.*\] test log 2/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		self::doCritLog('test log 3', $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_CRIT] \[.*\] test log 3/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		self::doFatalLog('test log 4', $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_FATAL] \[.*\] test log 4/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		self::doInfoLog('test log 5', $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_INFO] \[.*\] test log 5/', $response);
		$this->assertEquals(1, $matchesLogFormat);
	}
	
	public function testLoggingWithoutEcho(){
		_Log::$logEcho = false;
		_Log::$logLevel = _DEBUG;
		$rc = self::doDebugLog('test log 1', $response);
		$this->assertTrue($rc);
		
		$rc = self::doWarnLog('test log 2', $response);
		$this->assertTrue($rc);
		
		$rc = self::doCritLog('test log 3', $response);
		$this->assertTrue($rc);
		
		$rc = self::doFatalLog('test log 4', $response);
		$this->assertTrue($rc);
		
		$rc = self::doInfoLog('test log 5', $response);
		$this->assertTrue($rc);
	}
	
	public function testNULLObjectLog() {
		_Log::$logEcho = true;
		_Log::$logLevel = _DEBUG;
		$rc = self::doDebugLog(NULL, $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_DEBUG] \[.*\] NULL - Object is not set/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		$rc = self::doInfoLog(NULL, $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_INFO] \[.*\] NULL - Object is not set/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		$rc = self::doWarnLog(NULL, $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_WARN] \[.*\] NULL - Object is not set/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		$rc = self::doCritLog(NULL, $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_CRIT] \[.*\] NULL - Object is not set/', $response);
		$this->assertEquals(1, $matchesLogFormat);
		
		$rc = self::doFatalLog(NULL, $response);
		$matchesLogFormat = preg_match('/\[\*\*\* _Log \*\*\*\] \[_FATAL] \[.*\] NULL - Object is not set/', $response);
		$this->assertEquals(1, $matchesLogFormat);
	}


	private static function doDebugLog($msg, &$response = NULL){
		ob_start();
		$rc = _Log::debug($msg);
		$response = ob_get_contents();
		ob_clean();
		return $rc;
	}
	private function doInfoLog($msg, &$response = NULL){
		ob_start();
		$rc = _Log::info($msg);
		$response = ob_get_contents();
		ob_clean();
		return $rc;
	}
	private function doWarnLog($msg, &$response = NULL){
		ob_start();
		$rc = _Log::warn($msg);
		$response = ob_get_contents();
		ob_clean();
		return $rc;
	}
	private function doCritLog($msg, &$response = NULL){
		ob_start();
		$rc = _Log::crit($msg);
		$response = ob_get_contents();
		ob_clean();
		return $rc;
	}
	private function doFatalLog($msg, &$response = NULL){
		ob_start();
		$rc = _Log::fatal($msg);
		$response = ob_get_contents();
		ob_clean();
		return $rc;
	}
}

_Config::finish();
?>