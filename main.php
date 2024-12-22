<?php
define('ROOT_PATH', realpath(__DIR__ ));
require_once "database/connection.php";

// Contains all the global config
class Config {
    // All the application paths
    public static $paths = [
        "views" => ROOT_PATH . "\app\\views\\",
        "controllers" => ROOT_PATH . "\\app\\controllers\\",
        "models" => ROOT_PATH . "\\app\\models\\",
        "images" => "/LMS - Copy/public/images/",
        "js" => "/LMS - Copy/public/js/",
        "database" => ROOT_PATH . "\\database\\connection.php",
        "index" => "/LMS - Copy/public/index.php",
    ];

    public static $database = [
        "host" => "localhost", 
        "username" => "root", 
        "password" => "Dg$11029",
        "dbname" => "library_db",
        "port" => "3306"
    ];

    public static function getViewPath(String $entity, String $view)
    {
        return Config::$paths["views"] . $entity . "\\" . $view;
    }
 
    public static function getControllerPath(String $controller)
    {
        return Config::$paths["controllers"] . $controller;
    }

    public static function getModelPath(String $model)
    {
        return Config::$paths["models"] . $model;
    }
    
    public static function getImagePath(String $image)
    {
        return Config::$paths["images"] . $image;
       
    }

    public static function getjsPath(String $js)
    {
        return Config::$paths["js"] . $js;
       
    }

    public static function getdbPath()
    {
        return Config::$paths["database"];
       
    }

    public static function redirect($url) {
        header("Location: $url");
        exit();
    }
    public static function indexPath() {
        return Config::$paths["index"];
    }


   
}

