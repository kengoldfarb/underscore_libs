<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * 
 * Library to get general info
 *
 * @package _Libs
 * @subpackage _Info
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

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