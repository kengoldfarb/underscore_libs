<?php
require_once 'PHPUnit/Autoload.php';
require_once(realpath(dirname(__FILE__) . '/../') . '/' . '_lib/_Config.php');
_Config::init();

require_once(_LIB . '_Exception.php');
require_once(_LIB . '_KeyValue.php');

class _ExceptionTest extends PHPUnit_Framework_TestCase{
	public function testExceptionConstruct(){
		$ex = new _Exception('test message', 13, NULL);
		$this->assertEquals($ex->getMessage(), 'test message');
		$this->assertEquals($ex->getCode(), 13);
	}
}

_Config::finish();
?>