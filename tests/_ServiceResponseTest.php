<?php
require_once 'PHPUnit/Autoload.php';
require_once(realpath(dirname(__FILE__) . '/../') . '/' . '_lib/_Config.php');

if(!defined('_TEST_ENV')){
	define('_TEST_ENV', 'local');
}

_Config::init(_TEST_ENV);
require_once(_LIB . '_Log.php');
require_once(_LIB . '_ServiceResponse.php');

class _ServiceResponseTest extends PHPUnit_Framework_TestCase{
	public function testSerializeJSON(){
		
	}
	
	public function testSerializeXML(){
		$data = array(
			'single' => 'hello!',
			'arr_in_arr' => array(0,1,2,3,4,5)
		);
		$ret = _ServiceResponse::_success($data, FALSE, _ServiceResponse_XML);
		$this->assertNotEquals(FALSE, $ret);
	}
}

_Config::finish();
?>