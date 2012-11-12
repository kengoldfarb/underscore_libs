<?php
class _Rand{
	private static $lettersNums = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
	
	public static function _getRand($min, $max){
		return rand($min, $max);
	}
	
	public static function _randString($length, $lettersNumbersOnly = false){
		$str = '';
		for($i = 0; $i < $length; $i++){
			if($lettersNumbersOnly){
				$str .= self::$lettersNums[self::_getRand(0, strlen(self::$lettersNums) - 1)];
			}else{
				$str .= self::_randCharacter();
			}
		}
		return $str;
	}
	
	// http://www.asciitable.com/
	public static function _randCharacter($start = 32, $end = 126){
		$amountToAdd = $end - $start;
		return chr($start + self::_getRand(0, $amountToAdd));
	}
}
?>