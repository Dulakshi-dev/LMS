<?php

require_once __DIR__ . '/../main.php';

class Database
{
    private static $connection; // Holds the database connection instance

    // Setup connection
    public static function setUpConnection()
    {
        if (!isset(self::$connection)) {
            self::$connection = new mysqli(
                Config::$database["host"],
                Config::$database["username"],
                Config::$database["password"],
                Config::$database["dbname"],
                Config::$database["port"]
            );

            // Check if the connection failed
            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
        }
    }

    // Inserts data into the database and returns the last inserted ID if successful
    public static function insert($q)
    {
        Database::setUpConnection();
        $result = Database::$connection->query($q);

        if ($result) {
            if (Database::$connection->insert_id) {
                return Database::$connection->insert_id;
            }
        }
        return null;
    }

    // Executes an UPDATE or DELETE query
    public static function ud($q)
    {
        Database::setUpConnection();
        Database::$connection->query($q);
    }

    // Executes a SELECT query and returns the result set
    public static function search($q)
    {
        Database::setUpConnection();
        $resultset = Database::$connection->query($q);

        // If the query fails, terminate execution and show the error
        if (!$resultset) {
            die("Query failed: " . Database::$connection->error);
        }
        return $resultset;
    }

    // Close connection
    public static function closeConnection()
    {
        if (self::$connection) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
