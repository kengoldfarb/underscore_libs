<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Utility functions to generate UUIDs
 *
 * @package _Libs
 * @subpackage _UUID
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

if (!defined('_BASE_PATH')) {
    define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}

class _UUID {

    /**
     * Gets a 36 character UUID in the format: 5c0c5a48-90c7-46b7-a836-36bc0502cc9b
     * 
     * Adapted from http://www.php.net/manual/en/function.uniqid.php#94959
     * 
     * @return string 36 character UUID
     */
    public static function getUUID($withHyphens = TRUE) {
        $format = '%04x%04x-%04x-%04x-%04x-%04x%04x%04x';
        if (!$withHyphens) {
            $format = '%04x%04x%04x%04x%04x%04x%04x%04x';
        }
        return sprintf($format,
                        // 32 bits for "time_low"
                        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
                        // 16 bits for "time_mid"
                        mt_rand(0, 0xffff),
                        // 16 bits for "time_hi_and_version",
                        // four most significant bits holds version number 4
                        mt_rand(0, 0x0fff) | 0x4000,
                        // 16 bits, 8 bits for "clk_seq_hi_res",
                        // 8 bits for "clk_seq_low",
                        // two most significant bits holds zero and one for variant DCE1.1
                        mt_rand(0, 0x3fff) | 0x8000,
                        // 48 bits for "node"
                        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }

    /**
     * Changes a 36 character (or 32 without hypens) UUID to binary
     * 
     * Adapted from http://stackoverflow.com/questions/2839037/php-mysql-storing-and-retrieving-uuids/2839147#2839147
     * 
     * @param string $charUUID The UUID to convert to binary
     * @return binary The UUID as a binary
     */
    public static function charUUIDToBinary($charUUID) {
        $binary = pack("h*", str_replace('-', '', $charUUID));
        return $binary;
    }

    /**
     * Changes a binary UUID into a 36 character (or 32 without hypens) UUID
     * 
     * Adapted from http://stackoverflow.com/questions/2839037/php-mysql-storing-and-retrieving-uuids/2839147#2839147
     * 
     * @param string $binaryUUID The UUID to convert to characters
     * @param string $withHyphens Whether to include the hyphens in the UUID.  Default is TRUE
     * @return binary The UUID as a string
     */
    public static function binaryUUIDToCharUUID($binaryUUID, $withHyphens = TRUE) {
        $strArr = unpack("h*", $binaryUUID);
        if (!isset($strArr[1])) {
            return FALSE;
        }
        if ($withHyphens) {
            $format = "$1-$2-$3-$4-$5";
        } else {
            $format = "$1$2$3$4$5";
        }
        $string = preg_replace("/([0-9a-f]{8})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{4})([0-9a-f]{12})/", $format, $strArr[1]);
        return $string;
    }

}