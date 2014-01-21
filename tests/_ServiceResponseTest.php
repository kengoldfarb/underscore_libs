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

require_once _LIB . '_ServiceResponse.php';

use _\_ServiceResponse;

class _ServiceResponseTest extends PHPUnit_Framework_TestCase {

    public function testSerializeJSON() {
        
    }

    public function testSerializeXML() {
        $data = array(
            'single' => 'hello!',
            'arr_in_arr' => array(0, 1, 2, 3, 4, 5)
        );
        $ret = _ServiceResponse::_success($data, FALSE, 'xml');
        $this->assertNotEquals(FALSE, $ret);
    }

}
