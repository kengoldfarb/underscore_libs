<?php
include('vendor/autoload.php');

use _\_ServiceResponse;

$objectsToRespondWith = array();
$objectsToRespondWith['numbers'] = array(1, 2, 3, 4, 5, 6, 7);
$objectsToRespondWith['complex_array'] = array("animal" => "dog", "country" => "US");

_ServiceResponse::_success($objectsToRespondWith);
//_ServiceResponse::_failure($objectsToRespondWith);
