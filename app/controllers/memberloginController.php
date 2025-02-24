<?php
session_start();
require_once __DIR__ . '/../../main.php';

class MemberLoginController
{

    private $loginModel;

    public function __construct()
    {
        require_once Config::getModelPath('loginmodel.php');
        $this->loginModel = new LoginModel();
    }

    public static function showHome()
    {
        require_once Config::getViewPath("member", 'home.php');
    }

}