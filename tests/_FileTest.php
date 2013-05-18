<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 *
 * @package _LibsTests
 * @subpackage _FileTest
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 * ************************************************************************************************ */

require_once 'PHPUnit/Autoload.php';

if (!defined('_LIB')) {
    define('_LIB', realpath(dirname(__FILE__) . '/../') . '/src/_Libs/');
}

require_once _LIB . '_File.php';

use _\_File;
use _\_FileConstants;

define('_FILETEST_TMP_FILE_LOCATION', '/tmp/_PHP_FILETEST_TMP_FILE');
//define('_FILETEST_TMP_FILE_LOCATION', _FILE_TMP);

define('_FILETEST_TEST_TEXT_1', 'Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.');

class _FileTest extends PHPUnit_Framework_TestCase {

    public function testFileConstruct() {
        $file = new _File(_FILETEST_TMP_FILE_LOCATION);
        $this->assertNotEquals($file, FALSE);
    }

    public function testFileWrite() {
        $file = new _File(_FILETEST_TMP_FILE_LOCATION);
        $rc = $file->writeToFile(_FILETEST_TEST_TEXT_1);
        $this->assertTrue($rc);
    }

    public function testReadFromFile() {
        $file = new _File(_FILETEST_TMP_FILE_LOCATION, _FileConstants::READ_WRITE_TRUNCATE_CREATE);
        $rc = $file->writeToFile(_FILETEST_TEST_TEXT_1);
        $this->assertTrue($rc);

        $readText = $file->readAllFromFile();
        $this->assertEquals(_FILETEST_TEST_TEXT_1, $readText);
    }

    public function testFileWriteStatic() {
        $rc = _File::_writeToFile(_FILETEST_TEST_TEXT_1, _FILETEST_TMP_FILE_LOCATION);
        $this->assertTrue($rc);
    }

    public function testFileReadStatic() {
        $rc = _File::_writeToFile(_FILETEST_TEST_TEXT_1, _FILETEST_TMP_FILE_LOCATION, _FileConstants::READ_WRITE_TRUNCATE_CREATE);
        $this->assertTrue($rc);

        $readText = _File::_readAllFromFile(_FILETEST_TMP_FILE_LOCATION);
        echo $readText;
        $this->assertEquals(_FILETEST_TEST_TEXT_1, $readText);
    }

}
