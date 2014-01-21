<?php
/**
* 
*/
class _Info{
    public static function _getUserIpAddr ()
    {
        $ip = 'unknown.ip.address';
        if(isset($_SERVER)){
	        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) // If server is behind a load balancer
			{
	            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
	            $ip = $_SERVER['REMOTE_ADDR'];
	        }
	    }
        return $ip;
    }
}