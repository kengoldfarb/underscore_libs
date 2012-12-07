<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Simple name/value pair storage
 *
 * @package _Libs
 * @subpackage _ViewData
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 * ************************************************************************************************ */

namespace _;

class _ViewData {

    /**
     * @var array | Key/Value array
     */
    private static $keyvals;

    /**
     * Sets
     * 
     * @param string $key | The unique key
     * @param object $value | The value (can be any object
     * @param bool &$isOverwrite (optional) | Passed by reference, will be set to TRUE if the $key is overwritten / FALSE if this is a new key that is set
     * @return boolean | TRUE on success
     */
    public static function set($key, $value, &$isOverwrite = FALSE) {
        if (!isset(self::$keyvals)) {
            self::$keyvals = array();
        }
        if (isset(self::$keyvals[$key])) {
            $isOverwrite = TRUE;
        } else {
            $isOverwrite = FALSE;
        }
        self::$keyvals[$key] = $value;
        return TRUE;
    }

    /**
     * Gets the value corresponding to the $key
     * 
     * @param type $key
     * @return object | Returns the 'value' for a 'key' as an object on success | Empty string if key does not exist
     */
    public static function get($key) {
        if (isset(self::$keyvals) && isset(self::$keyvals[$key])) {
            return self::$keyvals[$key];
        }
        return '';
    }

    /**
     * Resets / clears all data
     */
    public static function clear() {
        self::$keyvals = array();
    }

}
