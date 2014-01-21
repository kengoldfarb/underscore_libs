<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 *
 * @package _LibsTests
 * @subpackage _ExceptionTest
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

require_once 'PHPUnit/Autoload.php';

if (!defined('_LIB')) {
    define('_LIB', realpath(dirname(__FILE__) . '/../') . '/src/_Libs/');
}

require_once _LIB . '_Exception.php';

use _\_Exception;

class _ExceptionTest extends PHPUnit_Framework_TestCase {

    public function testExceptionConstruct() {
        $ex = new _Exception('test message', 13, NULL);
        $this->assertEquals($ex->getMessage(), 'test message');
        $this->assertEquals($ex->getCode(), 13);
    }

}
