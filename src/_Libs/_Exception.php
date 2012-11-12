<?php
class _Exception extends Exception{
	public function __construct($message='', $code=0, $previous=NULL) {
		parent::__construct($message, $code, $previous);
	}
	
}
?>