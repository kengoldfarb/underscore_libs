<?php
/**************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * @package _Libs
 * @subpackage _WebResponseIncludes
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 **************************************************************************************************/

namespace _\_includes;

class _WebResponseIncludes{
	// http://en.wikipedia.org/wiki/List_of_HTTP_status_codes
	public static $STATUS_CODES = array(
		200 => 'OK',
		301 => 'Moved Permanently',
		302 => 'Found',
		304 => 'Not Modified',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		415 => 'Unsupported Media Type',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
	);
	public static $DEFAULT_STATUS_CODES = array(
		
	);
}
?>