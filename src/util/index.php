<?php
namespace Util;

require_once("/app/php/src/vendor/jwt/JWT.php");
require_once("/app/php/src/vendor/jwt/ExpiredException.php");
require_once('/app/php/src/config/dotenv.php');
use Config\DotEnv;
use DateTimeImmutable;
use \Firebase\JWT\JWT;
use \Firebase\JWT\ExpiredException;
use Exception;

    class Hash {
        private string $secret;
        public function __construct() {
            (new DotEnv())->load(); 
            $this->secret = getenv('HASH_SECRET');
        }

        function hash($password) { 
            
            $peppered = hash_hmac("sha256", $password, $this->secret);
            $hash = password_hash($peppered, PASSWORD_DEFAULT);
            return $hash;
        }

        function verify($password , $hash) {
            $verify = false;
            $peppered = hash_hmac("sha256", $password, $this->secret);
            
            if (password_verify($peppered, $hash)) {
                $verify = true;
            }
            return $verify;
        }
    }

    class AccessToken {
        private string $secret;
        private array $algorithm;
        public function __construct() {
            (new DotEnv())->load(); 
            $this->secret = getenv('JWT_SECRET');
            $this->algorithum = array('HS256');
        }

        function hash($data) {   
            $time = new DateTimeImmutable();
            $issuedAt   = $time->getTimestamp();
            $expire     = $time->modify('+1 minutes')->getTimestamp(); 
            $payload = array(
                "data" => $data,
                'iat'  => $issuedAt,
                'exp'  => $expire,
            );
            $jwt = JWT::encode($payload, $this->secret);
            return $jwt;
        }

        function verify($jwt) {
            try{
                $payload = JWT::decode($jwt, $this->secret, $this->algorithum);
                $result = (array)$payload;
                return  $result;
            }
            catch (ExpiredException $e){
                return;
            } 
            catch(Exception $e) {   
                return;
            }
        }
    }
?>