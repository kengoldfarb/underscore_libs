<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Extension of the Exception class.  Doesn't currently do anything, but may be nice to tap into
 * in the future.  Currently has the same behavior as a normal Exception
 *
 * @package _Libs
 * @subpackage _Rand
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

class _Exception extends \Exception {

    public function __construct($message = '', $code = 0, $previous = NULL) {
        parent::__construct($message, $code, $previous);
    }

}
