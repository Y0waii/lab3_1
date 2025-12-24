<?php
namespace App;

class Database {
    private static $pdo = null;
    
    public static function getConnection() {
        if (self::$pdo === null) {
            $dsn = 'mysql:host=localhost;dbname=lab3_1;charset=utf8';
            self::$pdo = new \PDO($dsn, 'root', '', [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ]);
        }
        return self::$pdo;
    }
}
