<?php
 /**************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * 
 * The _Config class provides nuts & bolts behind environment configuration, pathing, benchmarking,
 * etc.
 * 
 * This class MUST BE USED if you are using the full _PHP MVC project
 * 
 * If you are using stand-alone libraries, this class is not necessary
 *
 * ** IF USING _PHP MVC **
 * To configure your environment see the following files:
 * /config/environments.php
 * /env/[environment_name].php
 * /config/version.php
 * 
 * 
 * @package _PHP / _Lib
 * @subpackage _Config
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 **************************************************************************************************/
namespace uPHP;
/**************************************************************************************************
 * BEGIN _Config CONSTANTS
 * 
 * ****WARNING: DO NOT EDIT UNLESS YOU'RE CHANGING THE _Log CLASS ITSELF****
 */
if(!defined('_BASE_PATH')){
	define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}

if(!defined('_ENABLE_FORCE_SSL')){
	define('_ENABLE_FORCE_SSL', TRUE);
}
define('_LOG_CONFIG_DEBUG', false);

if(_LOG_CONFIG_DEBUG){
	if(!file_exists(_BASE_PATH . '_Log.php')){
		error_log('[FATAL][_PHP] Required file does not exist: ' . _BASE_PATH . '_Log.php');
	}
	if(!file_exists(_BASE_PATH . 'config/version.php')){
		error_log('[FATAL][_PHP] Required file does not exist: ' . _BASE_PATH . 'config/version.php');
	}
	if(!file_exists(_BASE_PATH . 'config/environments.php')){
		error_log('[FATAL][_PHP] Required file does not exist: ' . _BASE_PATH . 'config/environments.php');
	}
}
/**
 * END _Log CONSTANTS
 **************************************************************************************************/

/**************************************************************************************************
 * The main configuration class for _PHP
 */
class _Config{
	private static $isSSL = FALSE;
	private static $env = FALSE;
	private static $isInitialized = FALSE;
	private static $environments;
	
	/**
	 * Main init function.  When using _PHP, the function: _Config::init()
	 * should be called at the top of EVERY controller or php script.  This is required if the full framework
	 * is being used and not just the stand-alone libraries
	 * 
	 * @param string $environmentOverride (optional)
	 * @return bool | TRUE on success / FALSE on failure
	 */
	public static function init($environmentOverride = FALSE){
		// Prevent init() being called more than once
		if(self::$isInitialized){
			return FALSE;
		}

		self::setProjectDirectoryConstants();
		
		// Include other required config files
//		require_once(_LIB . '_Log.php');
		_Log::crit('logging htest');
		require_once(_LIB . '_ViewData.php');
		require_once(_LIB . '_Exception.php');
		require_once(_LIB . '_DefaultExceptionHandler.php');
		require_once(_BASE_PATH . 'config/version.php');
		require_once(_BASE_PATH . 'config/environments.php');
		if($environmentOverride === FALSE){
			self::setEnvironment();
		}else{
			self::setEnvironment($environmentOverride);
		}
		self::setIsSSL();
		
		$rc = @session_start();
		if($rc !== TRUE){
			_Log::warn('Unable to initialize session');
		}
		
		self::$isInitialized = TRUE;
		
		
		
		// Bootstrap
		require_once(_BASE_PATH . 'config/bootstrap.php');
		
		return TRUE;
	}
	
	/**
	 * Should be called at the end of EVERY controller or php script.  This is required if the full framework
	 * is being used and not just the stand-alone libraries
	 * 
	 */
	public static function finish(){
		
	}
	
	/**
	 * Method to check whether the connection is over SSL
	 * 
	 * @return bool | TRUE if over SSL / FALSE if not over SSL (or SSL status was unable to be determined)
	 */
	public static function isSSL(){
		return self::$isSSL;
	}
	
	/**
	 * When called, will require that the current connection is over SSL.
	 * If it is not over SSL, it will redirect to SSL
	 * If it is unable to redirect, it will throw an _Exception
	 * 
	 * @param bool $enableForceSSL (optional) | Override to the _ENABLE_FORCE_SSL constant.  In most cases you will not need to pass this variable
	 * @throws _Exception | If the required server variables are not set
	 */
	public static function requireSSL($enableForceSSL = _ENABLE_FORCE_SSL){
		if($enableForceSSL){
			if(!self::isSSL()){
				if(isset($_SERVER) && isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])){
					header("HTTP/1.1 301 Moved Permanently");
					header("Location: https://".$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
					exit();
				}else{
					throw new _Exception('$_SERVER variables are not set.  Unable to require SSL');
				}
			}
		}
	}
	
	/**
	 * BEGIN PRIVATE CLASS FUNCTIONS
	 */
	
	/**
	 * Defines paths to the models, controllers, views, and lib directories
	 */
	private static function setProjectDirectoryConstants(){
		define('_WEBROOT', _BASE_PATH . 'www/');
		define('_MODELS', _BASE_PATH . 'www/models/');
		define('_CONTROLLERS', _BASE_PATH . 'www/models/');
		define('_VIEWS', _BASE_PATH . 'www/views/');
		define('_LIB', _BASE_PATH . '_lib/');
	}
	
	/**
	 * Sets whether the connection is happening over SSL
	 */
	private static function setIsSSL(){
		if (isset($_SERVER)) {
			// Check if SSL is enabled
			if(isset($_SERVER['HTTPS']) && $_SERVER["HTTPS"] == "on"){
				self::$isSSL = true;
			}
		}
	}
	
	/**
	 * Sets the environment variable and include() for environment config file
	 * 
	 * @param string $environmentOverride | When set, will override the environment instead of setting it based on $_SERVER['SERVER_NAME']
	 * @return boolean | TRUE on success / FALSE on failure
	 */
	private static function setEnvironment($environmentOverride = FALSE){
		if($environmentOverride !== false){
			$env = $environmentOverride;
			$filename = _BASE_PATH . 'env/' . $environmentOverride . '.php';
			if(!file_exists($filename)){
				_Log::fatal('*** Environment Override ' . $environmentOverride . ' is invalid. ***');
				return FALSE;
			}
			require_once($filename);
		}else{
			if(!isset($GLOBALS['environments'])){
				throw new _Exception('*** Environment not set.  Check /env/environments.php ***');
			}
			
			self::$environments = $GLOBALS['environments'];
			
			$defaultConfig = isset($GLOBALS['environments']['_DEFAULT_']) ? $GLOBALS['environments']['_DEFAULT_'] : FALSE;
			
			// Get server name
			if (isset($_SERVER) && isset($_SERVER['SERVER_NAME'])) {
				$serverName = $_SERVER['SERVER_NAME'];
				if (isset(self::$environments[$serverName])) {
					$env = self::$environments[$serverName];
				} elseif($defaultConfig !== FALSE) {
					_Log::warn('*** SERVER_NAME (' . $serverName . ') does not match any defined environments. Using default. ***');
					$env = $defaultConfig;
				}else{
					_Log::fatal('*** SERVER_NAME (' . $serverName . ') does not match any defined environments and no default was set. ***');
					return FALSE;
				}
			} elseif($defaultConfig !== FALSE) {
				_Log::warn('*** SERVER_NAME is not set.  Please set this variable in apache or use the $environmentOverride option on _Config::init().  Using default configuration.');
				$env = $defaultConfig;
			}
			
			$filename = _BASE_PATH . 'env/' . $env . '.php';
		}
		
		self::$env = $env;
		if(!file_exists($filename)){
			_Log::fatal('*** Environment file does not exist: ' . $filename . ' ***');
			return FALSE;
		}
		$filename = _BASE_PATH . 'env/' . $env . '.php';
		require_once($filename);
		
		return TRUE;
	}
}
?>