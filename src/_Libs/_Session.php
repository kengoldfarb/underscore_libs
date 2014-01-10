<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Manage sessions
 * 
 * @package _Libs
 * @subpackage _Session
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

if (!defined('_BASE_PATH')) {
    define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}

class _Session {

    public static function isActive() {
        // Only available in PHP 5.4
        if (defined('session_status')) {
            _LogLib::debug('using session_status');
            /*
             * session_status() returns:
             * PHP_SESSION_DISABLED if sessions are disabled.
             * PHP_SESSION_NONE if sessions are enabled, but none exists.
             * PHP_SESSION_ACTIVE if sessions are enabled, and one exists.
             */
            switch (session_status()) {
                case PHP_SESSION_ACTIVE:
                    return TRUE;
                    break;
                default:
                    return FALSE;
                    break;
            }
        } else {
            _LogLib::debug('NOT using session_status');
            return isset($_SESSION);
        }
        return FALSE;
    }

    public static function set($key, $val) {
        if (!self::isActive()) {
            self::startSession();
            if (!self::isActive()) {
                return FALSE;
            }
        }
        $_SESSION[$key] = $val;
        return TRUE;
    }

    public static function get($key) {
        if (!self::isActive()) {
            self::startSession();
            if (!self::isActive()) {
                return FALSE;
            }
        }
        return $_SESSION[$key];
    }

    public static function startSession() {
        return @session_start();
    }

    public static function destroySession() {
        @session_unset();
        @session_destroy();
        unset($_SESSION);
        return TRUE;
    }

}
