<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 *
 * @package _LibsTests
 * @subpackage _DbTest
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 * ************************************************************************************************ */

require_once 'PHPUnit/Autoload.php';

if (!defined('_LIB')) {
    define('_LIB', realpath(dirname(__FILE__) . '/../') . '/src/_Libs/');
}

require_once _LIB . '_Db.php';
require_once _LIB . '_Log.php';

use _\_Db;
use _\_DbConfig;
use _\_DbErrors;
use _\_Log;

class _DbTest extends PHPUnit_Framework_TestCase {

    public function testCreateDbObject() {
        $t = array('1', '2', '3');
        _Log::debug($t);

        $db = new _Db(_DbConfig::USERNAME, _DbConfig::PASSWORD);
        $this->assertEquals(gettype($db), 'object');

        $db = new _Db();
        $this->assertEquals(gettype($db), 'object');
    }

    public function testDbQuery() {
        $db = new _Db();
        $sql = 'SELECT * FROM _testtable LIMIT 0,100';
        $result = $db->query($sql);
        $this->assertThat($result, $this->logicalNot($this->equalTo(FALSE)));
    }

    public function testDbInsert() {
        // cleanup
        $this->clearDb();

        $db = new _Db();
        $sql = "INSERT INTO _testtable (_key, _value, created) VALUES ('key_1', 'val_1', now())";
        $result = $db->query($sql);
        $this->assertTrue($result);

        // cleanup
        $this->clearDb();
    }

    public function testDupErrorUsingExceptions() {
        $db = new _Db();
        // cleanup
        $this->clearDb();

        $sql = "INSERT INTO _testtable (_key, _value, created) VALUES ('key_1', 'val_1', now())";
        // first time should be good
        $result = $db->query($sql);
        $this->assertTrue($result);
        try {
            // second time should fail with exception
            $result = $db->query($sql);
        } catch (Exception $e) {
            $this->assertSame($e->getCode(), _DbErrors::QUERY_ERROR);
        }

        // cleanup
        $this->clearDb();
    }

    public function testDupErrorWithoutExceptions() {
        $db = new _Db();
        // cleanup
        $this->clearDb();

        $sql = "INSERT INTO _testtable (_key, _value, created) VALUES ('key_1', 'val_1', now())";
        // first time should be good
        $result = $db->query($sql, false);
        $this->assertTrue($result);
        // second time should fail
        $result = $db->query($sql, false);
        $this->assertFalse($result);

        // cleanup
        $this->clearDb();
    }

    public function testCount() {
        $db = new _Db();

        // Test 1
        $this->clearDb();
        $this->insertRows(1);

        $sql = "SELECT * FROM _testtable";
        // first time should be good
        $result = $db->query($sql, false);
        $this->assertEquals($db->count(), 1);
        $this->clearDb();


        // Test 2
        $this->clearDb();
        $this->insertRows(58);

        $sql = "SELECT * FROM _testtable";
        // first time should be good
        $result = $db->query($sql, false);
        $this->assertEquals($db->count(), 58);
        $this->clearDb();


        // Test 3
        $this->clearDb();
        $this->insertRows(2103);

        $sql = "SELECT * FROM _testtable";
        // first time should be good
        $result = $db->query($sql, false);
        $this->assertEquals($db->count(), 2103);
        $this->clearDb();


        // Test 4
        $this->clearDb();

        $sql = "SELECT * FROM _testtable";
        // first time should be good
        $result = $db->query($sql, false);
        $this->assertEquals($db->count(), 0);
        $this->clearDb();
    }

    public function testGetRow() {
        // Test 3
        $this->clearDb();
        $this->insertRows(1000);
        $db = new _Db();
        for ($i = 0; $i < 1000; $i++) {
            $sql = "SELECT * FROM _testtable WHERE _key='key_$i'";
            $db->query($sql);
            $row = $db->getRow();
            $this->assertEquals("val_$i", $row['_value']);
        }
    }

    public function testEscape() {
        $db = new _Db();
        $str = "somethin' is goin' on";
        $escapedStr = $db->escape($str);

        $this->assertEquals("somethin\' is goin\' on", $escapedStr);
    }

    /**
     * Static tests
     */
    public function testStaticQuery() {
        $result = _Db::_query("SELECT * FROM _testtable");
        $this->assertThat($result, $this->logicalNot($this->equalTo(FALSE)));
    }

    public function testStaticCount() {
        // Test 1
        $this->clearDb();
        $this->insertRows(1);

        $sql = "SELECT * FROM _testtable";
        // first time should be good
        $result = _Db::_query($sql);
        $this->assertEquals(_Db::_count(), 1);
        $this->clearDb();


        // Test 2
        $this->clearDb();
        $this->insertRows(58);

        $sql = "SELECT * from _testtable";
        // first time should be good
        $result = _Db::_query($sql);
        $this->assertEquals(_Db::_count(), 58);
        $this->clearDb();


        // Test 3
        $this->clearDb();
        $this->insertRows(2103);

        $sql = "SELECT * from _testtable";
        // first time should be good
        $result = _Db::_query($sql);
        $this->assertEquals(_Db::_count(), 2103);
        $this->clearDb();


        // Test 4
        $this->clearDb();

        $sql = "SELECT * from _testtable";
        // first time should be good
        $result = _Db::_query($sql);
        $this->assertEquals(_Db::_count(), 0);
        $this->clearDb();
    }

    public function testStaticGetRow() {
        // Test 3
        $this->clearDb();
        $this->insertRows(1000);
        $db = new _Db();
        for ($i = 0; $i < 1000; $i++) {
            $sql = "SELECT * FROM _testtable WHERE _key='key_$i'";
            $db->query($sql);
            $row = $db->getRow();
            $this->assertEquals("val_$i", $row['_value']);
        }
    }

    public function testStaticEscape() {
        $str = "somethin' is goin' on";
        $escapedStr = _Db::_escape($str);

        $this->assertEquals("somethin\' is goin\' on", $escapedStr);
    }

    private function insertRows($numRows = 1) {
        $db = new _Db();

        for ($i = 0; $i < $numRows; $i++) {
            $k = 'key_' . $i;
            $v = 'val_' . $i;
            $sql = "INSERT INTO _testtable (_key, _value, created) VALUES ('$k', '$v', now())";
            $db->query($sql, false);
        }
    }

    private function clearDb() {
        $db = new _Db();
        $sql = "TRUNCATE TABLE _testtable";
        $result = $db->query($sql);
        $this->assertTrue($result);
    }

//	public function 
}
