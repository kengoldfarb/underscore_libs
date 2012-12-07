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

require_once _LIB . '_ViewData.php';

use _\_ViewData;

class _ViewDataTest extends PHPUnit_Framework_TestCase {

    public function testSet() {
        _ViewData::clear();
        $rc = _ViewData::set('testkey', 'testval');
        $this->assertTrue($rc);
    }

    public function testGet() {
        _ViewData::clear();
        $rc = _ViewData::set('testkey', 'testval');
        $this->assertTrue($rc);

        $val = _ViewData::get('testkey');
        $this->assertEquals('testval', $val);
    }

    public function testOverwrite() {
        _ViewData::clear();
        $rc = _ViewData::set('testkey', 'testval', $isSet);
        $this->assertTrue($rc);
        $this->assertFalse($isSet);

        $rc = _ViewData::set('testkey', 'testval NEW', $isSet);
        $this->assertTrue($rc);
        $this->assertTrue($isSet);
    }

}
