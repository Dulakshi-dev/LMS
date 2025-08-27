<?php
require_once __DIR__ . '/../main.php';

class Database
{
    private static $connection;

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

            if (self::$connection->connect_error) {
                die("Connection failed: " . self::$connection->connect_error);
            }
        }
    }

    public static function insert($query, $params = [], $types = "")
    {
        self::setUpConnection();

        if ($params) {
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $id = $stmt->insert_id;
            $stmt->close();
            return $id ?: null;
        } else {
            $result = self::$connection->query($query);
            return $result ? self::$connection->insert_id : null;
        }
    }

    public static function ud($query, $params = [], $types = "")
    {
        self::setUpConnection();

        if ($params) {
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $affected = $stmt->affected_rows;
            $stmt->close();
            return $affected;
        } else {
            $result = self::$connection->query($query);
            return $result ? self::$connection->affected_rows : 0;
        }
    }

    public static function search($query, $params = [], $types = "")
    {
        self::setUpConnection();

        if ($params) {
            $stmt = self::$connection->prepare($query);
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();
            return $result;
        } else {
            $result = self::$connection->query($query);
            if (!$result) die("Query failed: " . self::$connection->error);
            return $result;
        }
    }

    public static function closeConnection()
    {
        if (self::$connection) {
            self::$connection->close();
            self::$connection = null;
        }
    }
}
