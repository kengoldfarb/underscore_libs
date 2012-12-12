<?php
//define(_LOG_DO_DEBUG_BACKTRACE, true);
//include('vendor/autoload.php');
require_once '../src/_autoload.php';

use _\_Log;

_Log::debug('Hey there!  This is a log that uses the built in error_log() function.');

$obj = array(1, 2, 7, 4, 2, 'blah' => 'something');
_Log::fatal($obj);