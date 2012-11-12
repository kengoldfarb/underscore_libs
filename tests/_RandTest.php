<?php
require_once 'PHPUnit/Autoload.php';
require_once(realpath(dirname(__FILE__) . '/../') . '/' . '_lib/_Config.php');

if(!defined('_TEST_ENV')){
	define('_TEST_ENV', 'local');
}

_Config::init(_TEST_ENV);
require_once(_LIB . '_Log.php');
require_once(_LIB . '_Rand.php');


class _RandTest extends PHPUnit_Framework_TestCase{	
	public function testGetRandNumber(){
		$hash = array();
		for($i = 0; $i < 2000; $i++){
			$r = _Rand::_getRand(0, 999999999);
			if(!isset($hash[$r])){
				$hash[$r] = 1;
			}else{
				$hash[$r]++;
			}
			$this->assertLessThanOrEqual(999999999, $r);
			$this->assertGreaterThanOrEqual(0, $r);
		}
		
		// We generated 2000 numbers.  Let's assume there may be some duplicates, but it's unlikely that there would be many
		$this->assertGreaterThanOrEqual(1950, count($hash));
		
	}
	
	public function testRandCharacter(){
		for($i = 0; $i < 2000; $i++){
			$c = _Rand::_randCharacter();
			if(!isset($hash[$c])){
				$hash[$c] = 1;
			}else{
				$hash[$c]++;
			}
			$code = ord($c);
			$this->assertGreaterThanOrEqual(32, $code);
			$this->assertLessThanOrEqual(126, $code);
		}
		// A perfect distribution would have 94 different chars.
		// Allow for the off chance that maybe 1 char isn't used in 2000 iterations
		$this->assertGreaterThanOrEqual(93, count($hash));
	}
	
	public function testRandString(){
		for($i = 1; $i < 1000; $i++){
			$str = _Rand::_randString($i);
			if(!isset($hash[$str])){
				$hash[$str] = 1;
			}else{
				$hash[$str]++;
			}
			$this->assertEquals($i, strlen($str));
		}
	}
}

_Config::finish();
?>