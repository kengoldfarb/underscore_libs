<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Contains utility functions related to SSL web requests
 *
 * @package _Libs
 * @subpackage _SSL
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

class _SSL {

    /**
     * Requires SSL by redirecting non-SSL requests to the corresponding SSL endpoint
     * i.e. http://foo.com/test REDIRECTS TO https://food.com/test
     * 
     * @throws _Exception If $_SERVER variables are not set
     */
    public static function _requireSSL() {
        if (!self::_isSSL()) {
            if (isset($_SERVER) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
                header("HTTP/1.1 301 Moved Permanently");
                header("Location: https://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
                exit();
            } else {
                _Log::fatal('$_SERVER variables are not set.  Unable to require SSL');
                throw new _Exception('$_SERVER variables are not set.  Unable to require SSL');
            }
        }
    }

    /**
     * Determines whether the connection is over SSL
     * 
     * @return boolean TRUE if the connection is SSL | FALSE if not SSL
     */
    public static function _isSSL() {
        // Check if SSL is enabled
        if (isset($_SERVER) && isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on") {
            return TRUE;
        }
        return FALSE;
    }

}
