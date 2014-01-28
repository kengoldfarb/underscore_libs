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
	/**
     * Gets the current user's IP address
     * 
     * @param object $anyObject
     * @return string | The users ip address or 'unknown.ip.address'
     */
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

    /**
     * Gets the current time in proper format for mysql
     * 
     * @return string | The current datetime formatted for mysql
     */
    public static function _mysqlNow() {
    	return date('Y-m-d H:i:s');
    }
}