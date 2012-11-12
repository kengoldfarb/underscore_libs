<?php
/**
 * A model of a key value paif
 */
class _KeyValue {
	public $key;
	public $value;
	
	public function __construct($key, $value) {
		$this->key = $key;
		$this->value = $value;
	}
}

?>
