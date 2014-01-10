<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Utility functions to generate randomness.  Random strings, random numbers, etc.
 * 
 * Useful when writing tests
 *
 * @package _Libs
 * @subpackage _Rand
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

class _Rand {

    private static $lettersNums = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';

    /**
     * Wrapper for php's rand() function.  Probably unnecessary
     * 
     * @param type $min
     * @param type $max
     * @return type
     */
    public static function _getRand($min, $max) {
        return rand($min, $max);
    }

    /**
     * Creates a random string of the specified length
     * 
     * @param int $length The length of the string
     * @param bool $lettersNumbersOnly Should only letters and numbers be used?
     * @return string A random string
     */
    public static function _randString($length, $lettersNumbersOnly = false) {
        $str = '';
        for ($i = 0; $i < $length; $i++) {
            if ($lettersNumbersOnly) {
                $str .= self::$lettersNums[self::_getRand(0, strlen(self::$lettersNums) - 1)];
            } else {
                $str .= self::_randCharacter();
            }
        }
        return $str;
    }

    /**
     * Returns a random character within the start and end ascii characters
     * 
     * See http://www.asciitable.com/
     * 
     * @param int $start The start ascii character number
     * @param int $end The end ascii character number
     * @return char A single character
     */
    public static function _randCharacter($start = 32, $end = 126) {
        $amountToAdd = $end - $start;
        return chr($start + self::_getRand(0, $amountToAdd));
    }

}
