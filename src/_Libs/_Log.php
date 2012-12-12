<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * 
 * The _Log class provides logging functions at 5 levels: _DEBUG, _INFO, _WARN, _CRIT, _FATAL
 * 
 * Any object may be passed to the logging functions.  If a complex object (array, object, etc.)
 * then the logger will log the object in the same format as the print_r() function.
 * 
 * Configuration options include setting:
 * 1. Log level (_LOG_LEVEL)
 * 2. Whether complex objects should be logged (_LOG_OBJECTS)
 * 3. Whether to 'echo' logs instead of using error_log() (_LOG_ECHO)
 * 4. Whether to throw an _Exception if error_log() fails (_LOG_USE_EXCEPTIONS)
 *
 * @package _Libs
 * @subpackage _Log
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 * ************************************************************************************************ */

namespace _;

if (!defined('_BASE_PATH')) {
    define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}

require_once(_BASE_PATH . '_includes/_LogIncludes.php');


/* * ************************************************************************************************
 * BEGIN USER OPTIONS
 * 
 * If you are using this class as as stand-alone library, change configuration options below.
 * 
 * If you are using this class as part of _PHP MVC and have defined your configuration options
 * in an environment file (ex. /env/local.php) then the below options will be ignored
 * 
 */
// Sets the log level.  Can be set to: _DEBUG, _INFO, _WARN, _CRIT, _FATAL
if (!defined('_LOG_LEVEL')) {
    define('_LOG_LEVEL', _DEBUG);
}

// Allows the logging of objects.  When set to true, objects will be logged in the format of print_r().  When set to false, only simple object types (strings, integers, etc.) will be logged.
if (!defined('_LOG_OBJECTS')) {
    define('_LOG_OBJECTS', true);
}

// Echos any errors to stdout instead of logging via error_log() function
if (!defined('_LOG_ECHO')) {
    define('_LOG_ECHO', false);
}

// If logging using error_log() fails should an exception be thrown?
if (!defined('_LOG_USE_EXCEPTIONS')) {
    define('_LOG_USE_EXCEPTIONS', true);
}

// If logging using error_log() fails should an exception be thrown?
if (!defined('_LOG_DO_DEBUG_BACKTRACE')) {
    define('_LOG_DO_DEBUG_BACKTRACE', false);
}

/**
 * END USER OPTIONS
 * ************************************************************************************************ */
class _Log {

    /**
     * @var _LOG_LEVEL | May be:  _DEBUG, _INFO, _WARN, _CRIT, _FATAL
     */
    public static $logLevel = _LOG_LEVEL;

    /**
     * @var bool 
     */
    public static $logObjects = _LOG_OBJECTS;

    /**
     * @var bool 
     */
    public static $logEcho = _LOG_ECHO;

    /**
     * @var bool 
     */
    public static $useExceptions = _LOG_USE_EXCEPTIONS;

    /**
     * Mapping of log level constants to strings
     * 
     * @var array 
     */
    protected static $logLevelToString = array(
        _FATAL => '_FATAL',
        _CRIT => '_CRIT',
        _WARN => '_WARN',
        _INFO => '_INFO',
        _DEBUG => '_DEBUG',
        _DEBUG_LIB => '_DEBUG_LIB'
    );
    protected static $numFileLogs = 0;

    /**
     * The last debug_backtrace() result
     * 
     * @var object
     */
    protected static $lastDebugBacktrace = FALSE;

    /**
     * Logs a message or complex object at the _DEBUG log level
     * 
     * @param object $anyObject
     * @return bool | TRUE on success / FALSE on failure
     */
    public static function debug($anyObject) {
        return self::writeToLog(_DEBUG, $anyObject);
    }

    /**
     * Logs a message or complex object at the _INFO log level
     * 
     * @param object $anyObject
     * @return bool | TRUE on success / FALSE on failure
     */
    public static function info($anyObject) {
        return self::writeToLog(_INFO, $anyObject);
    }

    /**
     * Logs a message or complex object at the _WARN log level
     * 
     * @param object $anyObject
     * @return bool | TRUE on success / FALSE on failure
     */
    public static function warn($anyObject) {
        return self::writeToLog(_WARN, $anyObject);
    }

    /**
     * Logs a message or complex object at the _CRIT log level
     * 
     * @param object $anyObject
     * @return bool | TRUE on success / FALSE on failure
     */
    public static function crit($anyObject) {
        return self::writeToLog(_CRIT, $anyObject);
    }

    /**
     * Logs a message or complex object at the _FATAL log level
     * 
     * @param object $anyObject
     * @return bool | TRUE on success / FALSE on failure
     */
    public static function fatal($anyObject) {
        return self::writeToLog(_FATAL, $anyObject);
    }

    /**
     * Returns the last debug_backtrace() that was run
     * 
     * @return object | The last debug_backtrace() run / FALSE if there is no previous debug_backtrace()
     */
    public static function getLastDebugBacktrace() {
        return self::$lastDebugBacktrace;
    }

    /**
     * Checks if the current log level is at or above the defined _LOG_LEVEL
     * 
     * @param type $level The log level
     * @return bool Whether or not a log should be written
     */
    protected static function shouldWriteToLogAtLevel($level) {
        if ($level >= self::$logLevel) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Common logging function.  This is the heart of the _Log class and actually builds the message
     * 
     * @param _LOG_LEVEL $level | May be:  _DEBUG, _INFO, _WARN, _CRIT, _FATAL
     * @param object $obj | The object to be logged
     * @return boolean | TRUE on success / FALSE on failure
     */
    protected static function writeToLog($level, $obj = NULL) {
        if (!self::shouldWriteToLogAtLevel($level)) {
            return FALSE;
        }

        if(_LOG_DO_DEBUG_BACKTRACE){
            self::$lastDebugBacktrace = debug_backtrace();
        }
        
        // Add level to message
        $level = self::$logLevelToString[$level];
        $msg = "***** [ _Log ][ $level ] ";

        // TODO fix formatting of message here
        // Add trace/location to message
        if (_LOG_DO_DEBUG_BACKTRACE && self::$lastDebugBacktrace !== FALSE && isset(self::$lastDebugBacktrace[1]) && isset(self::$lastDebugBacktrace[1]['file']) && isset(self::$lastDebugBacktrace[1]['line'])) {
            $msg.= "[";
            $debugBacktraceLength = count(self::$lastDebugBacktrace);
            $first = true;
            
            for ($i = $debugBacktraceLength; $i >= 0; $i--) {
                if (isset(self::$lastDebugBacktrace[$i]) && isset(self::$lastDebugBacktrace[$i]['file'])) {
                    $file = self::_getFile(self::$lastDebugBacktrace[$i]['file']);

                    if (!$first) {
                        $msg .= ' <-- ';
                    }

                    $msg .= '(' . $file . ' : ' . self::$lastDebugBacktrace[$i]['line'] . ')';

                    $first = false;
                }
            }
            $msg .= ']';
        }

        $msg .= ' [ ';
        $doLogAtEnd = TRUE;
        $class = @get_class($obj);
        if (!isset($obj)) {
            $msg .= " NULL - Object is not set";
        } elseif ($class == '_Exception' || $class == 'Exception') {
            $msg .= $obj->getTraceAsString() . "\n";
            $msg .= 'Exception: ' . $obj->getLine() . ':' . self::_getFile($obj->getFile()) . ' | ' . $obj->getMessage();
        } else {
            $type = gettype($obj);
            switch ($type) {
                case NULL:
                    $msg.=' NULL';
                    break;

                case 'boolean':
                    $msg.=' ' . $obj ? 'TRUE' : 'FALSE';
                    break;

                case 'integer':
                case 'double':
                case 'string':
                    $msg.=' ' . $obj;
                    break;

                case 'array':
                case 'object':
                case 'resource':
                case 'unknown type':
                default:
                    $doLogAtEnd = FALSE;
                    if (!self::$logObjects) {
                        $msg .= "OBJECT | Logging of objects is turned off.  Set _LOG_OBJECTS to TRUE to enable";
                    } else {
                        $rc = self::_doLog("$msg [ (BEGIN)($type) ]");
                        if (self::$logEcho !== true) {
                            $obStarted = ob_start();
                            if ($obStarted) {
                                print_r($obj);
                                $output = ob_get_contents();
                                $arr = preg_split('/\\n/', $output);
                                foreach ($arr as $a) {
                                    $rc2 = self::_doLog($a);
                                    $rc = $rc && $rc2;
                                }
                                ob_clean();
                            }
                        } else {
                            print_r($obj);
                        }
                        $rc2 = self::_doLog("$msg (END)($type) ] *****'");
                        $rc = $rc && $rc2;
                        return $rc;
                    }
                    break;
            }
        }
        
        $msg .= ' ] *****';
        
        if ($doLogAtEnd) {
            return self::_doLog($msg);
        }
    }

    /**
     * Writes the actual log message to error_log() or echos the message depending on whether self::$logEcho is TRUE or FALSE
     * 
     * @param string $msg
     * @return boolean | TRUE on success / FALSE on failure
     * @throws _Exception | Only if error_log() fails and self::$useExceptions is TRUE
     */
    protected static function _doLog($msg) {
        if (self::$logEcho) {
            echo $msg . "\n";
            return TRUE;
        } else {
            if (!error_log($msg)) {
                if (self::$useExceptions) {
                    throw new _Exception('Unable to write to log using error_log().  Please check your configuration and permissions for log files');
                }
            } else {
                return TRUE;
            }
        }
    }

    /**
     * Extracts the filename from a full path
     * 
     * @param string $fullPath The full path name
     * @return string The file name
     */
    protected static function _getFile($fullPath) {
        $file = '';
        if (preg_match('/\/(_[A-Z].[A-Za-z]+)\.php$/', $fullPath, $matches)) {
            $file = isset($matches) && isset($matches[1]) ? $matches[1] : $file;
            self::$numFileLogs = 0;
        } else {
            switch (self::$numFileLogs) {
                case 0:
                    self::_doLog('Unable to get filename for: ' . $fullPath);
                    break;

                case 1:
                    self::_doLog('MULTIPLE ERRORS ENCOUNTERED GETTING FILE NAMES (no more will be logged): ' . $fullPath);
                    break;

                default:
                    break;
            }
            self::$numFileLogs++;
        }
        return $file;
    }

}
