<?php
/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * Should be used for logging within _Libs classes only
 * 
 * For application logging you should use the _Log class
 *
 * @package _Libs
 * @subpackage _LogLib
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 * ************************************************************************************************ */

namespace _;

if (!defined('_BASE_PATH')) {
	define('_BASE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
}

require_once(_BASE_PATH . '_includes/_LogIncludes.php');
require_once(_BASE_PATH . '_Log.php');

class _LogLib extends _Log {	
	/**
	 * WARNING: YOU SHOULD USE THE REGULAR _Log CLASS
	 * 
	 * This should be used only for _Lib logging
	 * 
	 * Logs a message or complex object at the _DEBUG_LIB log level
	 * 
	 * @param object $anyObject
	 * @return bool | TRUE on success / FALSE on failure
	 */
	public static function debug($anyObject){
		return self::writeToLog(_DEBUG_LIB, $anyObject);
	}
}

?>