# UnderscorePHP _Libs
### A set of useful, framework-agnostic PHP libraries to make life easier

[http://www.underscorephp.com](http://www.underscorephp.com)

The main purpose of these libraries are to be useful and flexible.  Use the ones you want, don't
use the others.  Plug and play so to speak.

## Installation
There are 2 ways to install...

* Using the [composer](http://getcomposer.org/) package manager
In your composer.json file add:

```json
{
    "require": {
        "kengoldfarb/underscore_libs": "2.*"
    }
}
```

Then install it (from the same directory as composer.json):
    php composer.phar install

* Download the latest source


## Usage
If you installed via composer:

```php
<?php
require_once 'vendor/autoload.php';

use _\_Crypt;
```

Otherwise, you can just use include the _autoload.php to load all libraries:
```php
require_once 'kengoldfarb/_autoload.php';
```

OR, just include the single library you want to use:
```php
require_once 'kengoldfarb/_libs/_Crypt.php';
```

Then call the functions:
```php
$encryptedString = _Crypt::_encryptAESPKCS7('some string to encrypt');
```

## License
[MIT](LICENSE)
