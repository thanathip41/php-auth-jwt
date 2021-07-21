<?php
namespace Http;

require_once('/app/php/src/http/response.php');
use Http\Response;
use Exception;

class Method {
    static function get () {
        $requestMethod = $_SERVER["REQUEST_METHOD"] === 'GET';
        if(!$requestMethod) {
            throw new Exception(Response::MethodNotAllowed());
        }
        return $requestMethod;
    }

    static function post () {
     
        $requestMethod = $_SERVER["REQUEST_METHOD"] === 'POST';
        if(!$requestMethod) {
            throw new Exception(Response::MethodNotAllowed());
        }
        return $requestMethod;
         
    }

    static function put () {
        $requestMethod = $_SERVER["REQUEST_METHOD"] === 'PUT';
        if(!$requestMethod) {
            throw new Exception(Response::MethodNotAllowed());
        }
        return $requestMethod;
    }
    static function delete () {
        $requestMethod = $_SERVER["REQUEST_METHOD"] === 'DELETE';
        if(!$requestMethod) {
            throw new Exception(Response::MethodNotAllowed());
        }
        return $requestMethod;
    }
}
?>