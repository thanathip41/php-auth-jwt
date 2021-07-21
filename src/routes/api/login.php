<?php
require_once("/app/php/src/connection/index.php");
require_once('/app/php/src/http/method.php');
require_once('/app/php/src/http/header.php');
require_once('/app/php/src/http/response.php');
require_once('/app/php/src/util/index.php');
use Connection\DB;
use Http\Method;
use Http\Response;
use Http\Header;
use Util\Hash;
use Util\AccessToken;

    try {
        Header::init();
        Method::post();
       
        $postBody = file_get_contents("php://input");
        $body = json_decode($postBody);
       
        $email = $body->email ?? '';
        $password = $body->password ?? '';
        if(!$email || !$password) {
            $errors = [
                "email" => "requreid",
                "password" => "required"
            ];
            if($email) unset($errors['email']);
            if($password) unset($errors['password']);
            throw Response::BadRequest($errors);
        }

        $db = DB::connection();
        $query = $db->prepare("SELECT * FROM users WHERE email = '$email' LIMIT 1");
        $query->execute();
        $user = $query->fetch(PDO::FETCH_ASSOC);

        if(!$user) throw Response::Unauthorized();
        $hash = $user['password'];
        $verify = (new Hash())->verify($password,$hash);

        if(!$verify) throw Response::Unauthorized();

        unset($user['password']);

        $user_id = $user['id'];
        $query = $db->prepare("INSERT INTO oauth_access_tokens (user_id, revoked) VALUES ('$user_id' , 0)");
        $query->execute();
        $oauthId = $db->lastInsertId();
        $accessToken = (new AccessToken())->hash($oauthId);

        echo json_encode([
            "success" => true,
            "user" => $user,
            "acccess_token" => $accessToken
        ]);
        return;
    } catch (Exception $err) {
        echo $err->getMessage();
        return;
    }
?>