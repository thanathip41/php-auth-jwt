<?php
require_once('/app/php/src/http/header.php');
require_once('/app/php/src/http/response.php');
use Http\Header;
use Http\Response;


try {
    Header::init();
    Response::NotFound();
} catch (Exception $err) {
    echo $err->getMessage();
}
?>