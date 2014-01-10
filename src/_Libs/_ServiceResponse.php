<?php

/* * ************************************************************************************************
 * _PHP <http://www.underscorePHP.com>
 * 
 * Meant for outputting a 'success' or 'failure' message along with other objects.
 * 
 * Useful when creating an API.
 * 
 * Note that XML requires the 'XML_Serializer' pear library
 * 
 *
 * @package _Libs
 * @subpackage _ServiceResponse
 * @author Ken Goldfarb <hello@kengoldfarb.com>
 * @license <http://opensource.org/licenses/MIT> MIT
 * 
 * ************************************************************************************************ */

namespace _;

if (!defined('_BASE_PATH')) {
    define('_BASE_PATH', realpath(dirname(__FILE__) . '/') . '/');
}


class _ServiceResponse {

    /**
     * Create a service response of success
     * 
     * @param array $objects An object or array of objects
     * @param bool $echoResponse Whether to echo out of response
     * @param string $format 'json' or 'xml'
     * @return string The response
     */
    public static function _success($objects, $echoResponse = TRUE, $format = 'json') {
        return self::_doResponse('success', $objects, $echoResponse, $format);
    }

    /**
     * Create a service response of failure
     * 
     * @param array $objects An object or array of objects
     * @param bool $echoResponse Whether to echo out of response
     * @param const $format _ServiceResponse_JSON or _ServiceResponse_XML
     * @return string The response
     */
    public static function _failure($objects, $echoResponse = TRUE, $format = _ServiceResponse_JSON) {
        return self::_doResponse('failure', $objects, $echoResponse, $format);
    }

    /**
     * Builds the response
     * 
     * @param type $type
     * @param type $objects
     * @param type $echoResponse
     * @param type $format
     * @return type
     */
    private static function _doResponse($type, $objects, $echoResponse, $format) {
        $ret = array();
        $ret['status'] = $type;
        if (is_array($objects)) {
            foreach ($objects as $k => $v) {
                $ret[$k] = $v;
            }
        } else {
            $ret[] = $objects;
        }

        switch ($format) {
            case 'xml':
                require_once 'XML/Serializer.php';
                $options = array(
                    "indent" => "    ",
                    "linebreak" => "\n",
                    "typeHints" => false,
                    "addDecl" => true,
                    "encoding" => "UTF-8",
                    "rootName" => "data",
//					"rootAttributes"  => array("version" => "0.91"),
                    "defaultTagName" => "item",
                    "attributesArray" => "_attributes"
                );

                $serializer = new \XML_Serializer($options);

                $rc = $serializer->serialize($ret);
                if ($rc !== TRUE) {
                    
                }
                $ret = $serializer->getSerializedData();
                break;

            case 'json':
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
