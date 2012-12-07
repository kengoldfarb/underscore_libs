<?php
include('vendor/autoload.php');

use _\_File;
$filename = "/tmp/" . date('Y-m-d-H-i-s');
_File::_writeToFile("This is a test", $filename);

$text = _File::_readAllFromFile($filename);
?>

<html>
	<body>
		<p>Text written to file: <?php echo $filename; ?></p>
		<p><pre><?php echo $text; ?></p>
	</body>
</html>