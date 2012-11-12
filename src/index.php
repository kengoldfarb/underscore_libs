<?php
require_once '../vendor/autoload.php';



use uLibs\_Crypt;

echo "index";
$keys = _Crypt::_generateRSAKeys();
print_r($keys);
?>