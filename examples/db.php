<?php
if(!defined('_LIB')){
	define('_LIB', realpath(dirname(__FILE__) . '/../') . '/src/_Libs/');
}

require_once(_LIB . '/_Db.php');

use _\_Db;

$host = 'localhost';
$username = 'theuser';
$password = 'thepassword';
$dbName = 'foo';

$db = new _Db($host, $username, $password, $dbName);
$id = 1;
$escapedId = $db->escape($id);

$sql = "SELECT * FROM bars WHERE id='$escapedId'";
$db->query($sql);

if($row = $db->getRow()){
	$thing = $row['col_name'];
}

echo $thing;
