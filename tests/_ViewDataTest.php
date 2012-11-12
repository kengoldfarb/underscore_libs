<?php
require_once 'PHPUnit/Autoload.php';
require_once(realpath(dirname(__FILE__) . '/../') . '/' . '_lib/_Config.php');

if(!defined('_TEST_ENV')){
	define('_TEST_ENV', 'local');
}

_Config::init(_TEST_ENV);
require_once(_LIB . '_Log.php');
require_once(_LIB . '_ViewData.php');

class _ViewDataTest extends PHPUnit_Framework_TestCase{
	public function testSet(){
		_ViewData::clear();
		$rc = _ViewData::set('testkey', 'testval');
		$this->assertTrue($rc);
	}
	
	public function testGet(){
		_ViewData::clear();
		$rc = _ViewData::set('testkey', 'testval');
		$this->assertTrue($rc);
		
		$val = _ViewData::get('testkey');
		$this->assertEquals('testval', $val);
	}
	
	public function testOverwrite(){
		_ViewData::clear();
		$rc = _ViewData::set('testkey', 'testval', $isSet);
		$this->assertTrue($rc);
		$this->assertFalse($isSet);
		
		$rc = _ViewData::set('testkey', 'testval NEW', $isSet);
		$this->assertTrue($rc);
		$this->assertTrue($isSet);
	}
}

_Config::finish();
?>