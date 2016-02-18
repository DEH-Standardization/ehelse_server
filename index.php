<?php
require_once "main/controllers/MainController.php";

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];
$api_version;
$body;

if( $_SERVER['PATH_INFO'] ){
    $temp = explode('/',$_SERVER['PATH_INFO']);
    if(count($temp) > 2){
        $api_version = $temp[1];
        $body = json_decode(file_get_contents('php://input'),true);
    }
}

$req = new MainController($api_version, $path, $method, $body);
echo $req->getResponse();