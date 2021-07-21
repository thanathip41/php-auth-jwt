<?php
namespace Http;
require_once('/app/php/src/connection/index.php');
require_once('/app/php/src/http/response.php');
require_once('/app/php/src/connection/index.php');
require_once('/app/php/src/util/index.php');

use Connection\DB;
use Http\Response;
use Util\AccessToken;
use Exception;
use PDO;

class Middleware {
    function auth () {
        $headers = apache_request_headers();

        $authorization = $headers['Authorization'] ?? throw Response::BadRequest();

        $accessToken = explode(" ",$authorization)[1] ?? throw Response::BadRequest();  

        $result = (new AccessToken())->verify($accessToken) ?? throw Response::Unauthorized();

        $id = $result['data'];

        $db = DB::connection();
        $query = $db->prepare("SELECT * FROM oauth_access_tokens WHERE id = '$id' AND revoked = 0 LIMIT 1");
        $query->execute();
        $oauth = $query->fetch(PDO::FETCH_ASSOC);
       
        if(!$oauth) throw Response::Unauthorized();

        $user_id = $oauth['user_id'];
        $query = $db->prepare("SELECT * FROM users WHERE id = '$user_id' LIMIT 1");
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if(!$user) throw Response::Unauthorized();

        return [$user,$id];
    }
}
?>