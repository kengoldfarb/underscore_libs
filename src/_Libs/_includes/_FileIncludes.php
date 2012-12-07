<?php
/**************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * @package _Libs
 * @subpackage _FileIncludes
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 **************************************************************************************************/

// http://php.net/manual/en/function.fopen.php
define('_FILE_READ_ONLY', 'r');
define('_FILE_READ_WRITE', 'r+');
define('_FILE_WRITING_ONLY_CREATE', 'w');
define('_FILE_READ_WRITE_TRUNCATE_CREATE', 'w+');
define('_FILE_WRITE_ONLY_END_OF_FILE_CREATE', 'a');
define('_FILE_READ_WRITE_END_OF_FILE_CREATE', 'a+');
define('_FILE_WRITE_ONLY_BEGIN_OF_FILE', 'x');
define('_FILE_READ_WRITE_BEGIN_OF_FILE', 'x+');
define('_FILE_WRITE_ONLY_NO_TRUNCATE_BEGIN_OF_FILE', 'c');
define('_FILE_READ_WRITE_NO_TRUNCATE_BEGIN_OF_FILE', 'c+');
define('_FILE_TMP', '_FILE_CREATE_TEMPORARY');
?>