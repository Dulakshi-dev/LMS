<?php

require_once __DIR__ . '/../main.php';

class Database {

    private static $connection;

    // Setup connection
    public static function setUpConnection() {
        if (!isset(self::$connection)) {
            self::$connection = new mysqli(
                Config::$database["host"],
                Config::$database["username"],
                Config::$database["password"],
                Config::$database["dbname"],
                Config::$database["port"]
            );

            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
        }
    }
   
    public static function iud($q){

        Database::setUpConnection();
        Database::$connection->query($q);
    }

    public static function search($q) {
        Database::setUpConnection();
        $resultset = Database::$connection->query($q);
   
        if (!$resultset) {
            die("Query failed: " . Database::$connection->error); // Add error handling
        }
   
        return $resultset;
   }  

    // Close connection
    public static function closeConnection() {
        if (self::$connection) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
?>
