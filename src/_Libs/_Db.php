<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * 
 * Database library that will connect to MySQL via the ext/mysqli functions
 *
 * @package _Libs
 * @subpackage _Db
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

if (!defined('_BASE_PATH')) {
    define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}

require_once(_BASE_PATH . '_Log.php');
require_once(_BASE_PATH . '_Exception.php');


/* * ************************************************************************************************
 * BEGIN _Db USER OPTIONS
 * 
 * To be used when this library is stand-alone
 * 
 */
 
class _DbConfig{
    // The DB host
    const HOST = '127.0.0.1';
    // The DB username
    const USERNAME = 'root';
    // The password for the user
    const PASSWORD = 'root';
    // The actual DB name
    const DB_NAME = 'underscorelibs';
    // The connection port
    const PORT = '3306';
    // The socket connection to use (can be NULL)
    const SOCKET = NULL;
    // Whether to throw exceptions when connection or query failures occur
    const USE_EXCEPTIONS = TRUE;
    // The DB charset to use
    const CHARSET = 'utf8';
    // Whether to log all SQL statements **IMPORTANT: THIS IS A DEBUG FUNCTION.
    // DO NOT SET THIS TO 'TRUE' IN A PRODUCTION ENVIRONMENT!!!
    const LOG_SQL = FALSE;
}

/**
 * END _Db USER OPTIONS
 * ************************************************************************************************ */

/* * ************************************************************************************************
 * BEGIN INTERNAL _Db CONSTANTS
 * 
 * There's probably no reason you'd ever need to edit this
 * 
 */
 
class _DbErrors{
    const CONNECTION_ERROR = -1300;
    const QUERY_ERROR = -1301;
    const ERROR = -1302;
}

/**
 * END INTERNAL _Db CONSTANTS
 * ************************************************************************************************ */
 
 
/* * ************************************************************************************************
 * The Database class
 */
class _Db {
    /* *******************************************************
     * 
     * Instatiated Object Methods
     * 
     * ***************************************************** */

    /**
     * The mysqli resource handle.  You can use any of the standard mysqli functions by using this handle
     * 
     * @var resource 
     */
    public $mysqli;
    private $isConnected = FALSE;
    private $host;
    private $username;
    private $password;
    private $dbName;
    private $port;
    private $socket;
    private $lastQuery = FALSE;
    private $lastResult = FALSE;
    private $lastRow = FALSE;
    private $lastCount = 0;

    /**
     * Creates a new _Db() object.
     * Note: A connection attempt will not be made until required
     * 
     * @param type $host
     * @param type $username
     * @param type $password
     * @param type $dbName
     * @param type $port
     * @param type $socket
     */
    public function __construct($host = _DbConfig::HOST, $username = _DbConfig::USERNAME, $password = _DbConfig::PASSWORD, $dbName = _DbConfig::DB_NAME, $port = _DbConfig::PORT, $socket = _DbConfig::SOCKET) {
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->dbName = $dbName;
        $this->port = $port;
        $this->socket = $socket;
    }

    /**
     * 
     * @param type $sql
     * @param bool $useExceptions Optional - If set to TRUE will throw an _Exception() | If set to FALSE will return FALSE on error
     * @return boolean|string
     * @throws _Exception with code _DB_QUERY_ERROR only if _DB_USE_EXCEPTIONS == TRUE or $useExceptions (override) == TRUE
     */
    public function query($sql, $useExceptions = _DbConfig::USE_EXCEPTIONS) {
        if (!$this->isConnected) {
            $rc = $this->createConnection();
            if ($rc === FALSE) {
                return FALSE;
            }
        }
        if (_DbConfig::LOG_SQL) {
            _Log::debug($sql);
        }

        try {
            $this->lastResult = $this->mysqli->query($sql);
        } catch (Exception $e) {
            if ($useExceptions) {
                throw new _Exception('Error executing query | ' . $e->getMessage(), _DbErrors::QUERY_ERROR, $e);
            } else {
                return FALSE;
            }
        }


        if ($this->lastResult === FALSE) {
            // Error occurred
            _Log::crit('Error occurred while executing query');

            if ($useExceptions) {
                throw new _Exception('Error executing query', _DbErrors::QUERY_ERROR);
            } else {
                return FALSE;
            }
        }


        $this->lastCount = $this->mysqli->affected_rows;
        return $this->lastResult;
    }

    /**
     * Gets the next row in the result set
     * 
     * @param object $result | A MySQLi link result object
     * @param bool $useExceptions | Override to throw an _Exception on error
     * @return array/bool | Returns the row as an associative array on success / FALSE on failure
     */
    public function getRow($result = NULL, $useExceptions = _DbConfig::USE_EXCEPTIONS) {
        if ($result === NULL) {
            $result = $this->lastResult;
        }
        if ($this->count() <= 0) {
            return FALSE;
        }
        $this->lastRow = $this->lastResult->fetch_assoc();
        return $this->lastRow;
    }

    /**
     * Checks how many rows were returned or affected
     * 
     * @return int | The number of affected rows or the number of rows returned depending on query type
     */
    public function count() {
        return $this->lastCount;
    }

    /**
     * Escapes variables/data in order to make it safe to use in a MySQL query
     * 
     * @param string $str | The string to escape
     * @return string/boolean | Returns the escaped string on success / FALSE on failure
     */
    public function escape($str) {
        if (!$this->isConnected) {
            $rc = $this->createConnection();
            if ($rc === FALSE) {
                return FALSE;
            }
        }
        return $this->mysqli->real_escape_string($str);
    }

    /**
     * Gets the last 'insert id' which will be the last id affected by an INSERT
     * or UPDATE statement
     * 
     * @return int The last insert id
     */
    public function lastId() {
        return $this->mysqli->insert_id;
    }

    /**
     * Creates the connection to the MySQL DB through the MySQLi interface
     * 
     * @param boolean $useExceptions (optional) | Override on whether to use exceptions.  If not passed will use _DB_USE_EXCEPTIONS
     * @return boolean | TRUE on success (connection was made) / FALSE on failure
     * @throws _Exception | If _DB_USE_EXCEPTIONS is TRUE, will throw an exception instead of returning
     * false on connection error
     */
    private function createConnection($useExceptions = _DbConfig::USE_EXCEPTIONS) {
        $this->mysqli = new \mysqli($this->host, $this->username, $this->password, $this->dbName, $this->port, $this->socket);
        if (mysqli_connect_errno()) {
            $msg = 'Unable to connect to MySQL | ' . mysqli_connect_error();
            if ($useExceptions) {
                _Log::fatal($msg);
                $_e = new _Exception($msg, 0, $e);
                throw $_e;
            } else {
                _Log::warn($msg);
            }
        }
        if (isset($this->mysqli) && $this->mysqli !== NULL) {
            // set charset
            $rc = $this->mysqli->set_charset(_DbConfig::CHARSET);
            if ($rc !== TRUE) {
                _Log::warn('Error setting character set');
            }
            $this->isConnected = true;
            return TRUE;
        }
        return FALSE;
    }

    /*     * ******************************************************
     * 
     * Static Methods
     * 
     * ***************************************************** */

    private static $_db;

    /**
     * Creates the connection to the MySQL DB through the MySQLi interface
     * 
     * @param boolean $useExceptions (optional) | Override on whether to use exceptions.  If not passed will use _DB_USE_EXCEPTIONS
     * @return boolean | TRUE on success (connection was made) / FALSE on failure
     * @throws _Exception | If _DB_USE_EXCEPTIONS is TRUE, will throw an exception instead of returning
     * false on connection error
     */
    public static function _createConnection($host = _DbConfig::HOST, $username = _DbConfig::USERNAME, $password = _DbConfig::PASSWORD, $dbName = _DbConfig::DB_NAME, $port = _DbConfig::PORT, $socket = _DbConfig::SOCKET) {
        self::$_db = new _Db($host, $username, $password, $dbName, $port, $socket);
    }

    /**
     * 
     * @param type $sql
     * @param bool $useExceptions Optional - If set to TRUE will throw an _Exception() | If set to FALSE will return FALSE on error
     * @return boolean|string
     * @throws _Exception with code _DB_QUERY_ERROR only if _DB_USE_EXCEPTIONS == TRUE or $useExceptions (override) == TRUE
     */
    public static function _query($sql) {
        if (!self::_isConnected()) {
            self::_createConnection();
        }
        return self::$_db->query($sql);
    }

    /**
     * Checks how many rows were returned or affected
     * 
     * @return int | The number of affected rows or the number of rows returned depending on query type
     */
    public static function _count() {
        if (!self::_isConnected()) {
            self::_createConnection();
        }
        return self::$_db->count();
    }

    /**
     * Gets the next row in the result set
     * 
     * @param object $result | A MySQLi link result object
     * @param bool $useExceptions | Override to throw an _Exception on error
     * @return array/bool | Returns the row as an associative array on success / FALSE on failure
     */
    public static function _getRow($result = NULL, $useExceptions = _DbConfig::USE_EXCEPTIONS) {
        if (!self::_isConnected()) {
            self::_createConnection();
        }
        return self::$_db->getRow($result, $useExceptions);
    }

    /**
     * Escapes variables/data in order to make it safe to use in a MySQL query
     * 
     * @param string $str | The string to escape
     * @return string/boolean | Returns the escaped string on success / FALSE on failure
     */
    public static function _escape($str) {
        if (!self::_isConnected()) {
            self::_createConnection();
        }
        return self::$_db->escape($str);
    }

    public static function _lastId() {
        return self::$_db->lastId();
    }

    private static function _isConnected() {
        if (isset(self::$_db) && self::$_db->isConnected === true) {
            return true;
        }
        return false;
    }

}
