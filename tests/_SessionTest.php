<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 *
 * @package _LibsTests
 * @subpackage _ServiceResponseTest
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

require_once 'PHPUnit/Autoload.php';

if (!defined('_LIB')) {
    define('_LIB', realpath(dirname(__FILE__) . '/../') . '/src/_Libs/');
}

require_once _LIB . '_Session.php';
require_once _LIB . '_Log.php';

use _\_Session;

class _SessionTest extends PHPUnit_Framework_TestCase {

    public function testSessionNotSet() {
        $this->assertFalse(_Session::isActive());
    }

    public function testSessionIsSet() {
        _Session::startSession();
        $this->assertTrue(_Session::isActive());
    }

}
