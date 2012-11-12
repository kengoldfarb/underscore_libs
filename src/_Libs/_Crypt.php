<?php
 /**************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * 
 * The _Crypt class provides AES and RSA encryption functions
 * 
 * 
 * @package _PHP / _Lib
 * @subpackage _Crypt
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://www.gnu.org/licenses/gpl.html> GNU General Public License Version 3
 * 
 **************************************************************************************************/
namespace _;
if(!defined('_BASE_PATH')){
	define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}

require_once(_BASE_PATH . 'includes/_CryptIncludes.php');
/**************************************************************************************************
 * BEGIN _Crypt CONSTANTS
 * 
 * You can edit the default constants here
 */
define('_CRYPT_CIPHER', MCRYPT_RIJNDAEL_256);
define('_CRYPT_MODE', MCRYPT_MODE_CBC);
define('_CRYPT_IV_SIZE', 256);
define('_CRYPT_AES_KEY', '_PHPTestKeyHere1234');


// WARNING: These should be used for testing only.  You should CHANGE THESE in your own application
define('_CRYPT_RSA_PUBLIC_KEY', '-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA4Vq3tpiH63PYBVW0bBNP
+RISZwStJchfZHIPlbsuQSER/3ePFgbsksAS02qMjZq1QwGrtfD8Tvlseb1G054a
sDCa4m4/TNVHgeE0GXMrHanlPbMF30x0ENbd3rmorbxbcWdImnfhcu0C4SnVqyrZ
Xk2fyJFqLx+TpldfK1wkjCicRzTesutB0lgCe501CRuo9q8hdAuVnq0VayfmuIDz
guMYc9svKA2OyFYxXNCokAz44AW2OJlpw515WJNBTybFjTN9hc5f3CPIFkw4RqkX
f01WSHP5hj1Zaj5bNiQ6PauOjP0fZKqSvjIQtmeicjI/54uuVtBgatdQm5mZqbuy
SwIDAQAB
-----END PUBLIC KEY-----');

define('_CRYPT_RSA_PRIVATE_KEY', '-----BEGIN RSA PRIVATE KEY-----
MIIEpQIBAAKCAQEA4Vq3tpiH63PYBVW0bBNP+RISZwStJchfZHIPlbsuQSER/3eP
FgbsksAS02qMjZq1QwGrtfD8Tvlseb1G054asDCa4m4/TNVHgeE0GXMrHanlPbMF
30x0ENbd3rmorbxbcWdImnfhcu0C4SnVqyrZXk2fyJFqLx+TpldfK1wkjCicRzTe
sutB0lgCe501CRuo9q8hdAuVnq0VayfmuIDzguMYc9svKA2OyFYxXNCokAz44AW2
OJlpw515WJNBTybFjTN9hc5f3CPIFkw4RqkXf01WSHP5hj1Zaj5bNiQ6PauOjP0f
ZKqSvjIQtmeicjI/54uuVtBgatdQm5mZqbuySwIDAQABAoIBAQDae+yPkJR+l6LB
kVVKTvMDRR0oWeVRM1Ig/WDUx1RR+rELYKwOLApjQOs/pBa/F1ZTr6P90rWcD8C2
yuaHNssjNQyFKWQfpswcRO4RGpKbwOMrjYzM8L+6r8cOMCw0vOBfM0a11DJ1DQr0
qG2q9PEnt9SJTUqIg3HQHPd8/2nDo62v/yWFUphmwMUsn83CgfAAEFbhmkv2CL3k
N6vP+GFkBOUITbUWeEgkqN7MwKWMfkFS+iW1LNWAE8yklAArCt+sn4Ro7SN/t+jI
1IV1eCN3e0rpeQiKaJsiztZFYOAEq/+G/8/6cLi5+Q9Z9DFQOnEn3Kb6uqatHre4
zv06Al9hAoGBAPqVktXhdY3A4rOvpoIoxYkyHcYJmOZ5cKsT5hjweT0W7a4scAHz
KiBmBit1S2z8AwdZnT0JXba0ED8vx8aZNx+x4C2gqaw0IBiwTNai1SM9w4gJCNsZ
uSlNzO1uYbuljPlBUp7isL7hjNoqzxDNqgbevH37MKHuKGT0PGja6enRAoGBAOY5
jXr2Z8rTpPBucjX7w2CEq5Dr5ESf9hCgdUQzJRlL27jMvnqjv91SyOE4vmQoc+If
A3FiDZe3VLzGUaWTRS9lwPhHODmGcFyo1Gs1e5PqYzO3Lmrkl7sDlIFTAIxDMhhN
oX05GaPbelGcuaVnQn64Gcp4cOi7dTeu7XtbXIVbAoGAax3rQdB5/tmYTzVj2Tny
jx5ESfaqTMNW3VrJPpn1SZ72hUDrtHms5WKXepZOYs0rwkWViJTrYYGBfHFBVe0C
+mKAMSD/xuQVYFhk0E+VCtaJMiqihX5uf3CJjGlmD9/J7nb1CYRgB4jFPDGWiFlS
OFOYgcYR2PbEf7tD45LI8TECgYEAmMeKpxYL7OKkq1Vv/3kSv+NrA2I3en74yTHi
gB68uNvJdQqSQUqXkaVVX3jLiCX8OQvBuiWKxarI1fl5xzpDCGArPdftbOdVe3gi
dv7oAlHiATjH2fHW6ylGDMhrLamN/ejOiQ4ygLWup6gs3qH206cSnnVs4FU+RXSV
bm5DqM8CgYEA4wGCStCY8BKey0YCnU9+RBJUc46Jf0WAedZapBX/aVQLg9e+DdQx
6cVVbKNmsBa0RupPk6kcJKb+3zofOEFolgbCv8YnXhX2YIsNhzyv8peUfn+bUrdf
jZ10TNRVnrubvoT9KU9e7TCVCDabBxPzGx5AoOwo2W5QS3QseZ/Spfw=
-----END RSA PRIVATE KEY-----');

class _Crypt {
	/**
	 * Encrypts a string using AES encryption with PKCS7 padding.
	 * Random IV is used
	 * 
	 * Adapted from comments in PHP manual:
	 * http://php.net/manual/en/function.mcrypt-encrypt.php
	 * 
	 * @param string $str The string to encrypt
	 * @param string $key (optional) The private key to encrypt with (note: anything over 24 chars will be chopped off)
	 * @param const $cipher (optional) One of the MCRYPT_RIJNDAEL_ ciphers
	 * @param cont $mode (optional) One of the MCRYPT_MODE modes
	 * @return string The encrypted string
	 */
	public static function _encryptAESPKCS7($str, $key = _CRYPT_AES_KEY, $cipher = _CRYPT_CIPHER, $mode = _CRYPT_MODE) {
		$key = self::_chopKeyForAES($key);
		$block = mcrypt_get_block_size($cipher, $mode);
		$pad = $block - (strlen($str) % $block);
		$str .= str_repeat(chr($pad), $pad);
		$iv = mcrypt_create_iv(mcrypt_get_iv_size($cipher, $mode), MCRYPT_DEV_URANDOM);
		$encrypted = mcrypt_encrypt($cipher, $key, $str, $mode, $iv);
		$ret = base64_encode($iv) . base64_encode($encrypted);
		return $ret;
	}
	
	/**
	 * Decrypts a string using AES encryption with PKCS7 padding.
	 * 
	 * Adapted from comments in PHP manual:
	 * http://php.net/manual/en/function.mcrypt-encrypt.php
	 * 
	 * @param string $str The string to encrypt
	 * @param string $key (optional) The private key to encrypt with (note: anything over 24 chars will be chopped off)
	 * @param const $cipher (optional) One of the MCRYPT_RIJNDAEL_ ciphers
	 * @param cont $mode (optional) One of the MCRYPT_MODE modes
	 * @return string The encrypted string
	 */
	public static function _decryptAESPKCS7($str, $key = _CRYPT_AES_KEY, $cipher = _CRYPT_CIPHER, $mode = _CRYPT_MODE) {
		$key = self::_chopKeyForAES($key);
		$ivLength = self::_getBase64IVLength($cipher, $mode);
		$iv = substr($str, 0, $ivLength - 1);
		$iv = base64_decode($iv);
		$str = substr($str, $ivLength - 1);
		$str = base64_decode($str);
		$str = mcrypt_decrypt($cipher, $key, $str, $mode, $iv);
		$block = mcrypt_get_block_size($cipher, $mode);
		$pad = ord($str[($len = strlen($str)) - 1]);
		return substr($str, 0, strlen($str) - $pad);
	}
	
	/**
	 * Combo RSA/AES encryption.  An AES key is generated and the actual data is encrypted
	 * with AES.  The AES key is then encrypted using RSA and prepended to the returned
	 * encrypted string
	 * 
	 * @param string $str The string to encrypt
	 * @param string $pubKey (optional) The RSA public key
	 * @return boolean|string FALSE on failure / The encrypted string on Success
	 */
	public static function _encryptRSA($str, $pubKey = _CRYPT_RSA_PUBLIC_KEY) {
		$aesKey = self::_generateAESKey();

		// encrypt string with AES
		$encrypted = self::_encryptAESPKCS7($str, $aesKey);
		
		// encrypt key with RSA
		if (openssl_public_encrypt($aesKey, $encryptedAESKey, $pubKey)) {
			return base64_encode($encryptedAESKey) . $encrypted;
		}

		return FALSE;
	}
	
	/**
	 * Combo RSA/AES decryption
	 * 
	 * @param string $str The encrypted string
	 * @param string $privKey (optional) The RSA private key
	 * @param cont $keyBits (optional) Whatever was used to encrypt.  Can be: _CRYPT_RSA_1024, _CRYPT_RSA_2048, _CRYPT_RSA_4096
	 * @return boolean|string FALSE on failure / The decrypted string on success
	 */
	public static function _decryptRSA($str, $privKey = _CRYPT_RSA_PRIVATE_KEY, $keyBits = _CRYPT_RSA_2048) {
		$keyLength = self::_getRSAAESKeyLength($keyBits);
		$encryptedKey = base64_decode(substr($str, 0, $keyLength));
		$encryptedStr = substr($str, $keyLength);

		// Decrypt aesKey
		if (!openssl_private_decrypt($encryptedKey, $decryptedKey, $privKey)) {
			return FALSE;
		}

		// decrypt data
		return self::_decryptAESPKCS7($encryptedStr, $decryptedKey);
	}
	
	/**
	 * Generates a random 24 char string to be used as an AES key
	 * 
	 * @return string Random 24 char string
	 */
	private static function _generateAESKey() {
		return _Rand::_randString(24, true);
	}

	/**
	 * Generates an initialization vector based on cipher and mode
	 * 
	 * @param const $cipher The cipher to be used
	 * @param const $mode The mode to be used
	 * @return string The initialization vector
	 */
	private static function _createIV($cipher, $mode) {
		$size = self::_getIVSize($cipher, $mode);
		return mcrypt_create_iv($size, MCRYPT_DEV_RANDOM);
	}
	
	/**
	 * Gets the IV size
	 * 
	 * @param cont $cipher
	 * @param cont $mode
	 * @return int The IV size
	 */
	private static function _getIVSize($cipher, $mode) {
		return mcrypt_get_iv_size($cipher, $mode);
	}

	/**
	 * Generates a RSA public/private keypair
	 *
	 * @param int $keyBits (optional) The number of key bits to use. Can be: _CRYPT_RSA_1024, _CRYPT_RSA_2048, _CRYPT_RSA_4096
	 * @return array Returns an associative array with 'public' and 'private' keys
	 */
	public static function _generateRSAKeys($keyBits = _CRYPT_RSA_2048) {
		$conf = array (
			'digest_alg'       => 'sha256',
			'private_key_type' => OPENSSL_KEYTYPE_RSA,
			'private_key_bits' => $keyBits,
			'encrypt_key'      => true,
		);
		
		
		$res = openssl_pkey_new($conf);
		$keys = array();
		openssl_pkey_export($res, $keys['private']);
		$publickey = openssl_pkey_get_details($res);
		$keys['public'] = $publickey["key"];
		return($keys);
	}
	
	/**
	 * Based on the key bit length, returns the string length of a base64 encoded AES key
	 * 
	 * @param const $keylength Can be: _CRYPT_RSA_1024, _CRYPT_RSA_2048, _CRYPT_RSA_4096
	 * @return int|boolean FALSE on failure / The string length on success
	 */
	private static function _getRSAAESKeyLength($keylength){
		switch($keylength){
			case _CRYPT_RSA_1024:
				return 172;
				
			case _CRYPT_RSA_2048:
				return 344;
				
			case _CRYPT_RSA_4096:
				return 684;
				
			default:
				return FALSE;
		}
	}

	/**
	 * Returns the # of characters in a base64 encoded IV
	 * based on the cipher and mode
	 * 
	 * @param type $cipher The cipher that is being used
	 * @param type $mode The mode
	 * @return int|boolean FALSE on failure / Size of IV (base64 encoded) on success
	 */
	private static function _getBase64IVLength($cipher, $mode) {
		$size = mcrypt_get_iv_size($cipher, $mode);
		switch ($size) {
			case 16:
				return 25;
			case 24:
				return 33;
			case 32:
				return 45;
			default:
				return FALSE;
		}
	}

	/**
	 * Since AES supports up to a 24 char key, this function will chop off anything after 24 characters
	 * 
	 * @param string $key The AES key
	 * @return string The chopped off key
	 */
	private static function _chopKeyForAES($key) {
		return(substr($key, 0, 24));
	}

}

?>