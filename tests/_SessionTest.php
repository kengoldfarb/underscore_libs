<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 *
 * @package _LibsTests
 * @subpackage _ServiceResponseTest
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 * ************************************************************************************************ */

require_once 'PHPUnit/Autoload.php';

if (!defined('_LIB')) {
    define('_LIB', realpath(dirname(__FILE__) . '/../') . '/src/_Libs/');
}

require_once _LIB . '_includes/_LogIncludes.php';
define('_LOG_LEVEL', _DEBUG_LIB);
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
