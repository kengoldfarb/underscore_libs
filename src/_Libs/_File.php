<?php

if (!defined('_BASE_PATH')) {
	define('_BASE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
}
require_once(_BASE_PATH . '_lib/_Log.php');
require_once(_BASE_PATH . '_lib/_Exception.php');
require_once(_BASE_PATH . '_lib/_includes/_FileIncludes.php');

class _File {

	public $filename;
	private $filePermissions;
	private $fh = FALSE;

	/**
	 * 
	 * @param type $filename (optional) The file to open or _FILE_TMP to generate a temporary file
	 * @param type $permissions (optional) The permissions to open the file
	 *  _FILE_READ_ONLY
	  _FILE_READ_WRITE
	  _FILE_WRITING_ONLY_CREATE
	  _FILE_READ_WRITE_TRUNCATE_CREATE
	  _FILE_WRITE_ONLY_END_OF_FILE_CREATE
	  _FILE_READ_WRITE_END_OF_FILE_CREATE
	  _FILE_WRITE_ONLY_BEGIN_OF_FILE
	  _FILE_READ_WRITE_BEGIN_OF_FILE
	  _FILE_WRITE_ONLY_NO_TRUNCATE_BEGIN_OF_FILE
	  _FILE_READ_WRITE_NO_TRUNCATE_BEGIN_OF_FILE
	 * 
	 * @return boolean TRUE on success of opening $filename / FALSE on failure to open $filename OR if no $filename was provided
	 */
	public function __construct($filename = NULL, $permissions = _FILE_READ_WRITE_END_OF_FILE_CREATE) {
		if ($filename === NULL) {
			_Log::debug('No filename specified');
			return FALSE;
		}
		return $this->openFile($filename, $permissions);
	}

	public function writeToFile($textToWrite, $filename = NULL) {
		if($filename !== NULL){
			$this->openFile($filename);
		}
		$rc = file_put_contents($this->filename, $textToWrite);
		if (is_numeric($rc) && $rc > 0) {
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Gets the current file permissions for the specified file
	 * 
	 * @param string $filename The file
	 */
	public function getFilePermissions($filename = NULL) {
		return $this->filePermissions;
	}

	public function readAllFromFile($filename = NULL) {
		if($filename !== NULL){
			$this->openFile($filename);
		}
		return file_get_contents($this->filename);
	}

	public function readByLineFromFile($filename = NULL) {
		throw new _Exception('readByLineFromFile() not yet implemented');
	}

	public function deleteFile($filename) {
		return unlink($filename);
	}

	/**
	 * Gets the location where temporary files are stored on the system
	 * 
	 * @return string The temporary file directory
	 */
	public function getTemporaryFileDirectory() {
		return sys_get_temp_dir();
	}

	/**
	 * Closes the current file handle
	 * 
	 * @return bool TRUE on success / FALSE on failure
	 */
	private function closeFile() {
		$this->filename = NULL;
		if($this->fh !== NULL && $this->fh !== FALSE){
			$rc = fclose($this->fh);
			return $rc;
		}else{
			return TRUE;
		}
	}

	/**
	 * 
	 * @param string $filename The file to open
	 * @param const $permissions The permissions
	 * @return boolean TRUE if file was opened / FALSE if file was unable to be opened
	 */
	private function openFile($filename, $permissions = _FILE_READ_WRITE_END_OF_FILE_CREATE) {
		$this->closeFile();
		if ($filename === NULL) {
			_Log::debug('Invalid filename specified');
			return false;
		}

		if ($filename == _FILE_TMP) {
			$this->filename = tempnam($this->getTemporaryFileDirectory(), '_');
			$this->fh = tmpfile();
		} else {
			$this->filename = $filename;
			$this->fh = fopen($filename, $permissions);
		}
		if ($this->fh !== FALSE) {
			$this->filePermissions = $permissions;
			return TRUE;
		}

		return FALSE;
	}
	
	private static $_file = FALSE;
	
	public static function _writeToFile($textToWrite, $filename, $permissions = _FILE_READ_WRITE_END_OF_FILE_CREATE){
		if(self::$_file === FALSE){
			self::_createFileObject($filename, $permissions);
		}
		return self::$_file->writeToFile($textToWrite);
	}
	
	public static function _readAllFromFile($filename, $permission = _FILE_READ_ONLY){
		if(self::$_file === FALSE){
			self::_createFileObject($filename, $permissions);
		}
		return self::$_file->readAllFromFile($filename);
	}
	
	private static function _createFileObject($filename = NULL, $permissions = _FILE_READ_WRITE_END_OF_FILE_CREATE){
		self::$_file = new _File($filename, $permissions);
	}
}

?>