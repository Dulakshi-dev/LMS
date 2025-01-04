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
        "images" => "/LMS/public/images/",
        "js" => "/LMS/public/js/",
        "database" => ROOT_PATH . "\\database\\connection.php",
        "index" => "/LMS/public/index.php",
        "services" => ROOT_PATH . "\\app\\services\\",
        "mail" => ROOT_PATH ."\\app\\services\\mail\\",
        "book_covers" => ROOT_PATH ."\\storage\\book_covers\\",
        "profile_img" => ROOT_PATH ."\\storage\\profile_img\\",
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

    public static function getServicePath(String $service)
    {
        return Config::$paths["services"] . $service;
    }

    public static function getMailPath(String $mail)
    {
        return Config::$paths["mail"] . $mail;
    }

    public static function getBookCoverPath()
    {
        return Config::$paths["book_covers"];
    }

    public static function getProfileImagePath()
    {
        return Config::$paths["profile_img"];
    }
}

