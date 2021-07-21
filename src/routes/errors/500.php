<?php
require_once('/app/php/src/http/header.php');
require_once('/app/php/src/http/response.php');
use Http\Header;
use Htpp\Response;

try {
    Header::init();
    Response::ServerError();
} catch (Exception $err) {
    echo $err->getMessage();
}

?>