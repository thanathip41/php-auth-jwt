<?php
require_once('/app/php/src/connection/index.php');
require_once('/app/php/src/http/middleware.php');
require_once('/app/php/src/http/method.php');
require_once('/app/php/src/http/header.php');
require_once('/app/php/src/http/response.php');
require_once('/app/php/src/util/index.php');
use Connection\DB;
use Http\Method;
use Http\Response;
use Http\Middleware;
use Http\Header;
use Util\AccessToken;
    try {
        Header::init();
        Method::delete();
    
        [,$oathId] = (new Middleware())->auth();

        $db = DB::connection();
        
        $query = $db->prepare("UPDATE oauth_access_tokens SET revoked = 1 WHERE id = '$oathId'");
        $query->execute();

        http_response_code(204);
        echo json_encode([
            "success" => true,
            "user" => $user
        ]);
        return;
    } catch (\Exception $err) {
        echo $err->getMessage();
        return;
    }
?>