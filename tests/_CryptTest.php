<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 *
 * @package _LibsTests
 * @subpackage _CryptTest
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

//require_once 'PHPUnit/Autoload.php';

if (!defined('_LIB')) {
    define('_LIB', realpath(dirname(__FILE__) . '/../') . '/src/_Libs/');
}

require_once(_LIB . '_Crypt.php');
require_once(_LIB . '_Rand.php');

use _\_Rand;
use _\_Crypt;

class _CryptTest extends PHPUnit_Framework_TestCase {

    public function testEncryptDecryptAESDefault() {
        for ($i = 0; $i < 5; $i++) {
            $txt = _Rand::_randString(_Rand::_getRand(1, 3000));
            $key = _Rand::_randString(_Rand::_getRand(1, 3000));
            $encrypted = _Crypt::_encryptAESPKCS7($txt, $key);
            $decrypted = _Crypt::_decryptAESPKCS7($encrypted, $key);
            $this->assertEquals($txt, $decrypted);
        }
    }

    public function testEncryptDecryptAES128() {
        for ($i = 0; $i < 5; $i++) {
            $txt = _Rand::_randString(_Rand::_getRand(1, 3000));
            $key = _Rand::_randString(_Rand::_getRand(1, 3000));
            $encrypted = _Crypt::_encryptAESPKCS7($txt, $key, MCRYPT_RIJNDAEL_128);
            $decrypted = _Crypt::_decryptAESPKCS7($encrypted, $key, MCRYPT_RIJNDAEL_128);
            $this->assertEquals($txt, $decrypted);
        }
    }

    public function testEncryptDecryptAES192() {
        for ($i = 0; $i < 5; $i++) {
            $txt = _Rand::_randString(_Rand::_getRand(1, 3000));
            $key = _Rand::_randString(_Rand::_getRand(1, 3000));
            $encrypted = _Crypt::_encryptAESPKCS7($txt, $key, MCRYPT_RIJNDAEL_192);
            $decrypted = _Crypt::_decryptAESPKCS7($encrypted, $key, MCRYPT_RIJNDAEL_192);
            $this->assertEquals($txt, $decrypted);
        }
    }

    public function testEncryptDecryptAES256() {
        for ($i = 0; $i < 5; $i++) {
            $txt = _Rand::_randString(_Rand::_getRand(1, 3000));
            $key = _Rand::_randString(_Rand::_getRand(1, 3000));
            $encrypted = _Crypt::_encryptAESPKCS7($txt, $key, MCRYPT_RIJNDAEL_256);
            $decrypted = _Crypt::_decryptAESPKCS7($encrypted, $key, MCRYPT_RIJNDAEL_256);
            $this->assertEquals($txt, $decrypted);
        }
    }

    public function testRSAGenerateKeys() {
        $keys = _Crypt::_generateRSAKeys(2048);
        echo "\n\n" . $keys['public'] . "\n\n";
        echo "\n\n" . $keys['private'] . "\n\n";
    }

    public function testRSAEncryptDecrypt1024() {
        $keys = _Crypt::_generateRSAKeys(1024);

        for ($i = 0; $i < 5; $i++) {
            $txt = _Rand::_randString(_Rand::_getRand(1, 3000));
            $key = _Rand::_randString(_Rand::_getRand(1, 3000));
            $encrypted = _Crypt::_encryptRSA($txt, $keys['public']);
            $decrypted = _Crypt::_decryptRSA($encrypted, $keys['private'], 1024);
            $this->assertEquals($txt, $decrypted);
        }
    }

    public function testRSAEncryptDecrypt2048() {
        $keys = _Crypt::_generateRSAKeys(2048);

        for ($i = 0; $i < 5; $i++) {
            $txt = _Rand::_randString(_Rand::_getRand(1, 3000));
            $key = _Rand::_randString(_Rand::_getRand(1, 3000));
            $encrypted = _Crypt::_encryptRSA($txt, $keys['public']);
            $decrypted = _Crypt::_decryptRSA($encrypted, $keys['private']);
            $this->assertEquals($txt, $decrypted);
        }
    }

    public function testRSAEncryptDecrypt4096() {
        $keys = _Crypt::_generateRSAKeys(4096);

        for ($i = 0; $i < 5; $i++) {
            $txt = _Rand::_randString(_Rand::_getRand(1, 3000));
            $key = _Rand::_randString(_Rand::_getRand(1, 3000));
            $encrypted = _Crypt::_encryptRSA($txt, $keys['public']);
            $decrypted = _Crypt::_decryptRSA($encrypted, $keys['private'], 4096);
            $this->assertEquals($txt, $decrypted);
        }
    }

}
