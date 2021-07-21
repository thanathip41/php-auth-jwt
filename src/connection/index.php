<?php 
namespace Connection;
require_once('/app/php/src/config/dotenv.php');
use Config\DotEnv;
use PDO;
class DB {
    static function connection () {
        try {
            (new DotEnv())->load();
            $DB_HOST = getenv('DB_HOST');
            $DB_USERNAME = getenv('DB_USERNAME');
            $DB_DATABASE= getenv("DB_DATABASE");
            $DB_PASSWORD = getenv("DB_PASSWORD");
            if(getenv('APP_ENV') === 'production') {
                $DB_HOST = getenv('DB_HOST_PROD');
                $DB_USERNAME = getenv('DB_USERNAME_PROD');
                $DB_DATABASE= getenv("DB_DATABASE_PROD");
                $DB_PASSWORD = getenv("DB_PASSWORD_PROD");
            }
            $db = new PDO("mysql:host={$DB_HOST}; dbname={$DB_DATABASE}", $DB_USERNAME, $DB_PASSWORD);
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $db;
        } catch(PDOEXCEPTION $e) {
            return $e;
        }
    }
}
?>