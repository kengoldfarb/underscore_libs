<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 *
 * @package _LibsTests
 * @subpackage _UUIDResponseTest
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
require_once _LIB . '_UUID.php';
require_once _LIB . '_Log.php';

use _\_UUID;

class _SessionTest extends PHPUnit_Framework_TestCase {

    public function testGetUUID() {
        $uuids = array();
        for ($i = 0; $i < 10000; $i++) {
            $uuid = _UUID::getUUID();
            $this->assertFalse($uuid == '');
            $this->assertFalse(isset($uuids[$uuid]));
            $this->assertEquals(1, preg_match('/[a-z0-9]{8}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{4}-[a-z0-9]{12}/', $uuid));
        }
    }

    public function testGetUUIDWithoutHyphens() {
        $uuids = array();
        for ($i = 0; $i < 10000; $i++) {
            $uuid = _UUID::getUUID(FALSE);
            $this->assertFalse($uuid == '');
            $this->assertFalse(isset($uuids[$uuid]));
            $this->assertEquals(1, preg_match('/[a-z0-9]{32}/', $uuid));
        }
    }

    public function testCharToBinaryAndBackWithHyphens() {
        for ($i = 0; $i < 10000; $i++) {
            $uuid = _UUID::getUUID();
            $binary = _UUID::charUUIDToBinary($uuid);
            $uuidFromBinary = _UUID::binaryUUIDToCharUUID($binary);
            $this->assertEquals($uuid, $uuidFromBinary);
        }
    }

    public function testCharToBinaryAndBackWithoutHyphens() {
        for ($i = 0; $i < 10000; $i++) {
            $uuid = _UUID::getUUID(FALSE);
            $binary = _UUID::charUUIDToBinary($uuid);
            $uuidFromBinary = _UUID::binaryUUIDToCharUUID($binary, false);
            $this->assertEquals($uuid, $uuidFromBinary);
        }
    }

}
