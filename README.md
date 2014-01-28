# UnderscorePHP _Libs

### A set of useful, framework-agnostic PHP libraries to make life easier

[http://www.underscorephp.com](http://www.underscorephp.com)

Goal: A set of libraries that are useful, flexible, and can be used within any framework.

## Installation

### Composer

Using the [composer](http://getcomposer.org/) package manager,
In your composer.json file add:

```json
{
    "require": {
        "kengoldfarb/underscore_libs": "~2.0.0"
    }
}
```

Then install it (from the same directory as composer.json):
    php composer.phar install

### Manual Installation

You can either download the [latest release](https://github.com/kengoldfarb/underscore_libs/releases) or you can just grab the source of an individual library and plug it in to your project.


## Usage
Here you'll find information and examples about each of the libraries.  These examples may not cover all permutations of how a library may be used.  For more advanced details please view the source code...hopefully you'll find that the comments are very verbose and easy to understand.

* [Logging](#log): _A logging class that also logs objects!_
* [Encryption](#crypt): _Simple AES or RSA (pub/private key) encryption_
* [UUID](#uuid): _Generate and work with uuids_
* [Database (MySQL)](#db): _MySQL database wrapper class with some nicities_
* [File](#file): _Makes working with files easier_
* [Random](#rand): _Generate random numbers, strings._
* [ServiceResponse](#service-response): _For API responses to generate consistent json or xml responses_
* [Session](#session): _A session wrapper to provide additional error checking_
* [SSL](#ssl): _Methods to check if SSL is active or require SSL_
* [Web Response](#web-response): _Some nice utility stuff like setting P3P headers, sending http status codes, etc._
* [Info](#info): _Extract information about a web request_

## Tests

Tests can be found in the 'tests' directory and can be run with phpunit.  If you've cloned this repository, you can run the tests...

```
$ cd /path/to/underscore_libs
$ composer install # The location of composer may differ on your computer
$ vendor/bin/phpunit tests/_CryptTest.php # Run the test
```

<a name="log" />
### Logging

The logging library  provides are log levels and the ability to log complex objects.

Logs are written via php's native ```error_log``` function.  The location of the log message may appear in a file, webserver log, syslog, etc. depending on your settings.

Change the **error_log** setting in your php.ini file to adjust the location.

#### Options

You can set the following options on the logger:

* ```_Log::$logLevel``` _Default: DEBUG_

The log level may be set to one of:

* ```_\_LogContants::FATAL```
* ```_\_LogContants::CRIT```
* ```_\_LogContants::WARN```
* ```_\_LogContants::INFO```
* ```_\_LogContants::DEBUG```

Once set, any logs at or above that level will be logged.  For example, if I set the level to INFO, any 'debug' logs will not be written but a 'warn' log would be written.

* ```_Log::$logObjects``` _Default: TRUE_

Boolean.  Whether or not to log complex objects.

* ```_Log::$logEcho``` _Default: FALSE_

Boolean.  Whether to use php's ```echo``` for log messages.  Can be handy for CLI scripts.

* ```_Log::$useExceptions``` _Default: FALSE_

Boolean.  Whether to throw an exception if an error log can't be written.  In most cases you probably want to leave this FALSE

#### Examples

```php
use _\_Log;
_Log::$logLevel = _\_LogContants::INFO;

_Log::debug('This is a debug message that will not be written because of log level');
_Log::info('This is an info message');
_Log::warn('This is a warning message');
_Log::crit('This is a critical message');
_Log::fatal('Oh snap.  This is a fatal error message');
```

<a name="crypt" />
### Encryption

The Crypt class provides AES and RSA encryption and decryption methods.

#### AES encryption with PKCS7 padding

##### Options

```_Crypt::_encryptAESPKCS7($textToEncrypt, $key, $cipher, $mode)```

**$textToEncrypt** _Required_

**$key** _Required_

**$cipher** _Optional_ 

Default: MCRYPT_RIJNDAEL_256
Allowed values are: MCRYPT_RIJNDAEL_128, MCRYPT_RIJNDAEL_192, MCRYPT_RIJNDAEL_256

[More information](http://www.php.net/manual/en/mcrypt.ciphers.php)

**$mode** _Optional_

Default: MCRYPT_MODE_CBC
Allowed values are: MCRYPT_MODE_CBC, MCRYPT_MODE_ECB, MCRYPT_MODE_CFB, MCRYPT_MODE_OFB, MCRYPT_MODE_NOFB, MCRYPT_MODE_STREAM

[More information](http://us3.php.net/manual/en/mcrypt.constants.php)

##### Basic Example

```php
use _\_Crypt;

// Set the secret key
$secretKey = "MySecretKey123";

// The text to encrypt
$textToEncrypt = "How much wood could a woodchuck chuck if a woodchuck could chuck wood?";

// The encrypted text
$encryptedText = _Crypt::_encryptAESPKCS7($textToEncrypt, $secretKey);

// The decrypted text (will match $textToEncrypt)
$decryptedText = _Crypt::_decryptAESPKCS7($encryptedText, $secretKey);
```

#### RSA encryption (public/private keys)

This type of encryption is perfect for situations where you need to encrypt something within an application that doesn't need to be decrypted within the application.  In that case you could keep the decryption (private) key off your application server for added security.

#### Generate RSA Public/Private keypair
Generate a keypair.  You can optionally pass in $keybits which may be one of: 1024, 2048, 4096

```
$keys = _Crypt::_generateRSAKeys();
$publicKey = $keys['public'];
$privateKey = $keys['private'];
```

#### RSA Encrypt/Decrypt
```
$textToEncrypt = "How much wood could a woodchuck chuck if a woodchuck could chuck wood?";
$encryptedText = _Crypt::_encryptRSA($textToEncrypt, $publicKey);
$decryptedText = _Crypt::_decryptRSA($encryptedText, $privateKey);
```

<a name="uuid" />
### UUID
This library provides functions for working with a UUID or "Universally Unique Identifier".

#### Generate a UUID

##### Options

$withHyphens _Default: TRUE_
This single option can be passed to the function that controls over whether or not the UUID will contain hyphens.

##### Example

```php
use _\_UUID;

$uuid = _UUID::getUUID();
```

#### Convert UUID string to binary representation

##### Example

```php
use _\_UUID;

$uuid = '55a0afe6-0e00-4c08-9fb9-7905f0a106b6';
$binaryUUID = _UUID::charUUIDToBinary($uuid);
```

#### Convert UUID in binary to string representation

##### Options

$withHyphens _Default: TRUE_
This single option can be passed to the function that controls over whether or not the UUID will contain hyphens.

##### Example

```php
use _\_UUID;

$uuid = '55a0afe6-0e00-4c08-9fb9-7905f0a106b6';
$binaryUUID = _UUID::charUUIDToBinary($uuid);

$strUUID = _UUID::binaryUUIDToCharUUID($binaryUUID);

// At this point, $uuid == $strUUID
```

<a name="db" />
### Database

Wrapper for the mysqli library.  Provides both regular and static class interfaces.  In most cases you'll want to use the static interface which will share the DB connection within your application.  This helps prevent the opening of uncessary connections.

#### Public variables

$mysqli
For non-static interfaces, you can access the 'mysqli' object directly.

**Note:** the below examples assume you're using the static version of the DB class.  The non-static versions of the functions have the same name without the beginning underscore.  I.e. _Log::_query($sql) corresponds to $db->query($sql)

#### Examples

##### Create a connection
```php
use _\_Db;
$host = 'localhost';
$user = 'root';
$pass = 'mysupersecretpassword';
$dbName = 'the_db';
$port = 3306;

_Db::_createConnection($host, $username, $password, $dbName, $port);
``

##### Create a query, escape it, get the number of rows and process the rows
```php
$sql = "select * from users";
$sql = _Db::_escape($sql);
$queryResult = _Db::_query($sql);

if($queryResult === FALSE) {
	// Handle error
}else{
	$numRows = _Db::_count();
	while($row = _Db::getRow()) {
		// Do something with $row
	}
}
```

##### Insert a new row into the db and get the resulting id
```php
$sql = "insert into users (name, username) values ('Ken', 'ken')";
$queryResult = _Db::_query($sql);
if($queryResult === FALSE) {
	// Handle error
}else{
	$userId = _Db::_lastId();
}
```

<a name="file" />
### File
Simplifies the task of writing and reading from files

#### Regular Methods

* writeToFile($textToWrite, $filename = NULL)
* getFilePermissions($filename = NULL)
* readAllFromFile($filename = NULL)
* readByLineFromFile($filename = NULL)
* deleteFile($filename)
* getTemporaryFileDirectory()

#### Static Methods

* _writeToFile($textToWrite, $filename, $permissions = _FileConstants::READ_WRITE_END_OF_FILE_CREATE)
* _readAllFromFile($filename, $permission = _FileConstants::READ_ONLY)

#### Options
Some of the methods will allow you to pass a constant specifying which permissions the file should be opened with.  The available (self explanitory) options are:
_FileConstants::READ_ONLY
_FileConstants::READ_WRITE
_FileConstants::WRITING_ONLY_CREATE
_FileConstants::READ_WRITE_TRUNCATE_CREATE
_FileConstants::WRITE_ONLY_END_OF_FILE_CREATE
_FileConstants::READ_WRITE_END_OF_FILE_CREATE
_FileConstants::WRITE_ONLY_BEGIN_OF_FILE
_FileConstants::READ_WRITE_BEGIN_OF_FILE
_FileConstants::WRITE_ONLY_NO_TRUNCATE_BEGIN_OF_FILE
_FileConstants::READ_WRITE_NO_TRUNCATE_BEGIN_OF_FILE

#### Examples

##### Read from file

```php
use _\_File;
$fileData = _File::_readAllFromFile('/tmp/myfile.txt');
if($fileData === FALSE) {
	// Handle error reading from file
}

echo $fileData;
```

##### Write to file

```php
use _\_File;
$writeSuccessful = _File::_writeToFile('Here is some text for myfile.txt', '/tmp/myfile.txt');
if($writeSuccessful) {
	echo $fileData;
}else{
	// Hand error writing file
}
```

<a name="info" />
### Info

General info and helper class.

#### Get the user's IP address

Gets the user's ip.  This should work behind load balancers.  It will first check for the ```HTTP_X_FORWARDED_FOR``` header for the ip address.  If that isn't set it will use ```REMOTE_ADDR```.

```php
use _\_Info;
$userIp = _Info::_getUserIpAddr();
```

#### Get the current datetime in a mysql friendly format

```php
use _\_Info;
$mysqlDatetime = _Info::_mysqlNow();
```

<a name="random" />
### Random

#### Get Random Number

**_getRand($min, $max)**

```php
use _\_Rand;
$min = 0;
$max = 100;
_Rand::_getRand($min, $max);
```

#### Get Random String

**_randString($length, $lettersNumbersOnly = false)**

```php
use _\_Rand;
$length = 10; // The number of characters in the string
$lettersNumbersOnly = true; // String will contain only letters and numbers
_Rand::_randString($length, $lettersNumbersOnly);
```

#### Get Random Character

**_randCharacter($start = 32, $end = 126)**

##### Parameters

**$start** The ascii character code to start from
**$end** The ascii character code to end at

See [http://www.asciitable.com](http://www.asciitable.com/) for a list of character codes

```
use _\_Rand;
$randChar = _Rand::_randCharacter();
```

<a name="serviceresponse" />
### Service Response

This library generates json or xml responses in a consistent format.

#### Parameters

**$objects** Any object to be serialized.  String, number, object, array, etc.
**$echoResponse** _optional_ Whether or not to echo the response
**$format** _optional_ 'json' or 'xml'

#### Success

**_success($objects, $echoResponse = TRUE, $format = 'json')**

```php
use _\_ServiceResponse;
_ServiceResponse::_success(array('hello' => 'world'));

// Result: {"status": "success", "hello": "world"}
```

#### Failure

**_failure($objects, $echoResponse = TRUE, $format = 'json')**

```php
use _\_ServiceResponse;
_ServiceResponse::_failure(array('reason' => 'server error'));

// Result: {"status": "failure", "reason": "server error"}
```

<a name="ssl" />
### SSL

TODO

<a name="session" />
### Session

TODO

<a name="webresponse" />
### Web Response

TODO

## Versioning

This project will follow the guidelines set forth in [Semantic Versioning](http://semver.org/);

## Contact

Author: [Ken Goldfarb](mailto:hello@kengoldfarb.com)

## License
[MIT](LICENSE)
