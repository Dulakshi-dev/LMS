<?php
define('ROOT_PATH', realpath(__DIR__));
require_once config::getdbPath();

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_lifetime', 0);
    session_start();
}

// Contains all the global config
class Config
{
    public const baseURL = "/LMS/";

    public const sourcePath = __DIR__;
    // All the application paths
    public static $paths = [
        "views" => Config::sourcePath . "\app\\views\\",
        "controllers" => Config::sourcePath . "\\app\\controllers\\",
        "models" => Config::sourcePath . "\\app\\models\\",
        "images" => Config::baseURL . "/public/images/",
        "js" => Config::baseURL . "/public/js/",
        "css" => Config::baseURL . "/public/css/",
        "database" => Config::sourcePath . "\\database\\connection.php",
        "index" => "/LMS/public/staff/index.php",
        "index_member" => "/LMS/public/member/index.php",
        "services" => Config::sourcePath . "\\app\\services\\",
        "mail" => Config::sourcePath . "\\app\\services\\mail\\",
        "book_covers" => Config::sourcePath . "\\storage\\book_covers\\",
        "staff_profile_img" => Config::sourcePath . "\\storage\\profile_img\\staff\\",
        "member_profile_img" => Config::sourcePath . "\\storage\\profile_img\\member\\",
        "news_image" => Config::sourcePath . "\\storage\\news\\",
        "logo" => Config::sourcePath . "\\storage\\logo\\",
        "logger" => Config::sourcePath . "\\core\\logger.php",
        "logs" => Config::sourcePath . "\\logs\\",

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

    public static function getControllerPath(String $entity, String $controller)
    {
        return Config::$paths["controllers"] . $entity . "\\" . $controller;
    }

    public static function getModelPath(String $entity, String $model)
    {
        return Config::$paths["models"] . $entity . "\\" . $model;
    }


    public static function getImagePath(String $image)
    {
        return Config::$paths["images"] . $image;
    }

    public static function getjsPath(String $js)
    {
        return Config::$paths["js"] . $js;
    }

    public static function getCssPath(String $css)
    {
        return Config::$paths["css"] . $css;
    }


    public static function getdbPath()
    {
        return Config::$paths["database"];
    }

    public static function redirect($url)
    {
        header("Location: $url");
        exit();
    }

    public static function indexPath()
    {
        return Config::$paths["index"];
    }

    public static function indexPathMember()
    {
        return Config::$paths["index_member"];
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

    public static function getStaffProfileImagePath()
    {
        return Config::$paths["staff_profile_img"];
    }

    public static function getMemberProfileImagePath()
    {
        return Config::$paths["member_profile_img"];
    }

    public static function getNewsImagePath()
    {
        return Config::$paths["news_image"];
    }

    public static function getLogoPath()
    {
        return Config::$paths["logo"];
    }
    
    public static function getLoggerPath()
    {
        return Config::$paths["logger"];
    }

        public static function getLogsPath()
    {
        return Config::$paths["logs"];
    }
}
