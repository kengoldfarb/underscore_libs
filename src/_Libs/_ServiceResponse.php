<?php

if(!defined('_BASE_PATH')){
	define('_BASE_PATH', realpath(dirname(__FILE__) . '/../') . '/');
}

require_once(_BASE_PATH . '_lib/_includes/_ServiceResponseIncludes.php');

class _ServiceResponse {
	public static function _success($objects, $echoResponse = TRUE, $format = _ServiceResponse_JSON) {
		return self::_doResponse('success', $objects, $echoResponse, $format);
	}
	
	public static function _failure($objects, $echoResponse = TRUE, $format = _ServiceResponse_JSON) {
		return self::_doResponse('failure', $objects, $echoResponse, $format);
	}

	private static function _doResponse($type, $objects, $echoResponse, $format) {
		$ret = array();
		$ret['status'] = $type;
		foreach ($objects as $k => $v) {
			$ret[$k] = $v;
		}

		switch ($format) {
			case _ServiceResponse_XML:
				require_once 'XML/Serializer.php';
				$options = array(
					"indent"          => "    ",
					"linebreak"       => "\n",
					"typeHints"       => false,
					"addDecl"         => true,
					"encoding"        => "UTF-8",
					"rootName"        => "data",
//					"rootAttributes"  => array("version" => "0.91"),
					"defaultTagName"  => "item",
					"attributesArray" => "_attributes"
				);

				$serializer = new XML_Serializer($options);

				$rc = $serializer->serialize($ret);
				if($rc !== TRUE){
					
				}
				$ret = $serializer->getSerializedData();
				break;

			case _ServiceResponse_JSON:
			default:
				$ret = json_encode($ret);
				break;
		}

		if ($echoResponse) {
			echo $ret;
		}
		return $ret;
	}

}

?>