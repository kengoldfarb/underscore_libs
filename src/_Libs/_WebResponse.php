<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Utility functions related to web responses like sending P3P headers,
 * redirecting users, etc.
 *
 * @package _Libs
 * @subpackage _WebResponse
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 * ************************************************************************************************ */

namespace _;

if (!defined('_BASE_PATH')) {
    define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}
require_once(_BASE_PATH . '_Log.php');
require_once(_BASE_PATH . '_Exception.php');
require_once(_BASE_PATH . '_includes/_WebResponseIncludes.php');

class _WebResponse {

    /**
     * Redirects to the specified url
     * 
     * @param string $url The url to redirect to
     */
    public static function _redirect($url) {
        header('Location: ' . $url);
    }

    /**
     * Sends headers to cache for the specified number of MINUTES
     * 
     * @param int $expire Cache duration in MINUTES
     */
    public static function _sendCacheHeaders($expire = 5) {
        if (!is_numeric($expire) || $expire < 1 || $expire > 240) {
            $expire = 5;
            _Log::warn("Attempt to send cache headers with invalid expiration");
        }
        header("Cache-Control: public"); // HTTP/1.1
        header("Expires: " . gmdate("D, d M Y H:i:s", time() + 60 * $expire) . " GMT");
        header("Last-Modified: " . gmdate('D, d M Y H:i:s') . " GMT");
    }

    /**
     * Sends P3P headers.  Can fix silly IE issues with downloading of files, using cookies, etc.
     * http://en.wikipedia.org/wiki/P3P
     */
    public static function _sendP3PHeaders() {
        header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"');
    }

    /**
     * Sends headers that this should NOT BE CACHED
     */
    public static function _sendDoNotCacheHeaders() {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control: no-store,no-cache, must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private", false);
    }

    /**
     * Sends the HTTP status code
     * 
     * 
     * @param int $code The code to send
     * @param type $customDescription (optional) The description of the response code
     */
    public static function _sendHTTPStatusCode($code, $customDescription = FALSE) {
        if (!preg_match('/\d\d\d/', $code)) {
            _Log::warn("Invalid status code: $code - Using default '200 OK' response");
            $code = 200;
        }

        if ($customDescription === FALSE) {
            $description = isset(_WebResponseIncludes::$STATUS_CODES[$code]) ? _WebResponseIncludes::$STATUS_CODES[$code] : '';
        } else {
            $description = $customDescription;
        }
        header("HTTP/1.0 $code $description");
    }

    /**
     * Sets the Content-type header
     * 
     * @param string $contentType The content type
     */
    public static function _setContentType($contentType) {
        header("Content-type: $contentType");
    }

}
