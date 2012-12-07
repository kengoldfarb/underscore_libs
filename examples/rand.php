<?php
include('vendor/autoload.php');

use _\_Rand;

$randString = _Rand::_randString(50);
echo "Random string: " . $randString;
?>