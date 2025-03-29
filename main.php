<?php
define('ROOT_PATH', realpath(__DIR__ ));
require_once "database/connection.php";

// Contains all the global config
class Config {

    public const baseURL = "/LMS/";

    public const sourcePath = __DIR__;
    // All the application paths
    public static $paths = [
        "views" => Config::sourcePath . "\app\\views\\",
        "controllers" => Config::sourcePath . "\\app\\controllers\\",
        "models" => Config::sourcePath . "\\app\\models\\",
        "images" => Config::baseURL . "/public/images/",
        "js" => Config::baseURL . "/public/js/",
        "database" => Config::sourcePath . "\\database\\connection.php",
        "index" => "/LMS/public/staff/index.php",
        "index_member" => "/LMS/public/member/index.php",
        "services" => Config::sourcePath . "\\app\\services\\",
        "mail" => Config::sourcePath ."\\app\\services\\mail\\",
        "book_covers" => Config::sourcePath ."\\storage\\book_covers\\",
        "staff_profile_img" => Config::sourcePath ."\\storage\\profile_img\\staff\\",
        "member_profile_img" => Config::sourcePath ."\\storage\\profile_img\\member\\",

    ];

    public static $database = [
        "host" => "localhost", 
        "username" => "root", 
        "password" => "",
        "dbname" => "library_db",
        "port" => "3306"
    ];

    public static function getViewPath(String $entity, String $view){
        return Config::$paths["views"] . $entity . "\\" . $view;
    }
 
    public static function getControllerPath(String $controller){
        return Config::$paths["controllers"] . $controller;
    }

    public static function getModelPath(String $model){
        return Config::$paths["models"] . $model;
    }
    
    public static function getImagePath(String $image){
        return Config::$paths["images"] . $image;
    }

    public static function getjsPath(String $js){
        return Config::$paths["js"] . $js;
    }

    public static function getdbPath(){
        return Config::$paths["database"];
    }

    public static function redirect($url) {
        header("Location: $url");
        exit();
    }

    public static function indexPath() {
        return Config::$paths["index"];
    }

    public static function indexPathMember() {
        return Config::$paths["index_member"];
    }

    public static function getServicePath(String $service){
        return Config::$paths["services"] . $service;
    }

    public static function getMailPath(String $mail){
        return Config::$paths["mail"] . $mail;
    }

    public static function getBookCoverPath(){
        return Config::$paths["book_covers"];
    }

    public static function getStaffProfileImagePath(){
        return Config::$paths["staff_profile_img"];
    }

    public static function getMemberProfileImagePath(){
        return Config::$paths["member_profile_img"];
    }
}

