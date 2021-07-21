<?php 
namespace Http;
use Exception;
class Response {

    static function handlerError ($message = '' ,$code = 500) {
        http_response_code($code);
        throw new Exception(json_encode([
                "success" => false,
                "message" => $message,
                "code" => $code
            ])
        );
    }

    static function BadRequest ($message = 'Bad Request') {
        self::handlerError($message , 400);
    }

    static function Unauthorized ($message = 'Unauthorized') {
        self::handlerError($message , 401);
    }

    static function Forbidden ($message = 'Forbidden') {
        self::handlerError($message , 403);
    }

    static function NotFound ($message = 'Not Found') {
        self::handlerError($message , 404);
    }

    static function MethodNotAllowed ($message = 'Method Not Allowed') {
        self::handlerError($message , 405);
    }

    static function ServerError ($message = 'Internal Server Error') {
        self::handlerError($message , 500);
    }
}
?>