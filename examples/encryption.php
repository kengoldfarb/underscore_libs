<?php
include('vendor/autoload.php');

use _\_Crypt;

$keys = _Crypt::_generateRSAKeys();

$textToEncryptRSA = 'I\'m encrypted with RSA';

$encryptedTextRSA = _Crypt::_encryptRSA($textToEncryptRSA, $keys['public']);

$decryptedTextRSA = _Crypt::_decryptRSA($encryptedTextRSA, $keys['private']);


$key = "someSecretKey";

$textToEncryptAES = 'I\'m encrypted with AES';

$encryptedTextAES = _Crypt::_encryptAESPKCS7($textToEncryptAES, $key);

$decryptedTextAES = _Crypt::_decryptAESPKCS7($encryptedTextAES, $key);
?>

<html>
	<body>
		<p>RSA Keys:</p>
		<p><pre><?php var_dump($keys); ?></pre></p>
	
		<p>RSA:</p>
		<p><pre>Encrypted: <?php echo $encryptedTextRSA; ?></pre></p>
		<p><pre>Decrypted: <?php echo $decryptedTextRSA; ?></pre></p>

		<p>AES:</p>
		<p><pre>Encrypted: <?php echo $encryptedTextAES; ?></pre></p>
		<p><pre>Decrypted: <?php echo $decryptedTextAES; ?></pre></p>
	</body>
</html>