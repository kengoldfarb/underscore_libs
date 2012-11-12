<?php

define('_WEBREQUEST_MIN_TIMEOUT_SECONDS', 1);
define('_WEBREQUEST_MAX_TIMEOUT_SECONDS', 120);

/**
 * A wrapper for making a CURL request
 */
class _WebRequest{
	private $ch;
	private $url;
	private $lastRC;
	
	/**
	 * 
	 * @param string $url (optional) The url that the request will be made to
	 */
	public function __construct($url = NULL){
		$this->url = $url;
		
		$rc = $this->ch = curl_init($url);
		if($rc === FALSE){
			_Log::crit("Unable to initalize CURL.  Check that it's installed on your system.");
			_Log::debug(curl_version());
		}
	}
	
	/**
	 * Manually set a CURL option
	 * See http://www.php.net/manual/en/function.curl-setopt.php
	 * 
	 * @param CURL_OPT $option (constant) A CURL option
	 * @param boolean TRUE on success / FALSE on failure 
	 */
	public function setOption($option, $value) {
		return curl_setopt($this->ch, $option, $value);
	}
	
	/**
	 * Executes the request
	 * 
	 * @param boolean $isPost (optional - default FALSE) Whether to send this as a POST request.
	 * If false, will be sent as GET
	 * @param array $postData If this is a POST request, the data that should be sent in a key/value pair array
	 * @return type
	 */
	public function makeRequest($isPost = false, $postData = array()) {
		if ($isPost) {
			curl_setopt($this->ch, CURLOPT_POST, 1);
			curl_setopt($this->ch, CURLOPT_POSTFIELDS, $postData);
		} else {
			curl_setopt($this->ch, CURLOPT_POST, 0);
		}

		$buffer = curl_exec($this->ch);
		$this->lastRC = curl_getinfo($this->ch, CURLINFO_HTTP_CODE);
		$this->buffer = $buffer;
		if ($buffer === false) {
			_Log::warn("Curl execution failed with RC: " . $this->lastRC);
		}
		return($buffer);
	}
	
	/**
	 * Gets additional information about the request
	 * 
	 * On success returns associative array with the following keys:
	 * "url"
	 * "content_type"
	 * "http_code"
	 * "header_size"
	 * "request_size"
	 * "filetime"
	 * "ssl_verify_result"
	 * "redirect_count"
	 * "total_time"
	 * "namelookup_time"
	 * "connect_time"
	 * "pretransfer_time"
	 * "size_upload"
	 * "size_download"
	 * "speed_download"
	 * "speed_upload"
	 * "download_content_length"
	 * "upload_content_length"
	 * "starttransfer_time"
	 * "redirect_time"
	 * "certinfo"
	 * "request_header"
	 * 
	 * @return mixed FALSE on failure / associative array on success
	 */
	public function getInfo() {
		return curl_getinfo($this->ch);
	}

	/**
	 * Sets the timeout for the request
	 * 
	 * Has additional check for high and low timeouts.  The thresholds for those checks
	 * can be set: _WEBREQUEST_MIN_TIMEOUT_SECONDS, _WEBREQUEST_MAX_TIMEOUT_SECONDS
	 * 
	 * @param int $seconds The number of seconds to wait for the request before timing out
	 * @return boolean TRUE on success / FALSE on failure
	 */
	public function setTimeout($seconds) {
		if (!is_int($seconds)) {
			_Log::warn("Timeout is not an integer");
			return false;
		}
		if ($seconds < _WEBREQUEST_MIN_TIMEOUT_SECONDS || $seconds > _WEBREQUEST_MAX_TIMEOUT_SECONDS) {
			_Log::warn("Timeout is very small or very large");
		}
		$rc = curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, $seconds);
		return $rc;
	}

	/**
	 * Sets or re-sets the url for the request
	 * 
	 * @param string $url The url that will be retrieved
	 */
	public function setUrl($url) {
		$this->url = $url;
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
	}

	/**
	 * Gets the last HTTP response code encountered
	 * 
	 * @return int The last HTTP code encountered during execution
	 */
	public function lastRC() {
		return($this->lastRC);
	}
	
	private function setOptions(){
		curl_setopt($this->ch, CURLOPT_URL, $this->url);
		curl_setopt($this->ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($this->ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($this->ch, CURLOPT_MAXREDIRS, 5);
	}
}
?>