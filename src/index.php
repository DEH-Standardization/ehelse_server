<?php
require_once "v1/main/controllers/MainController.php";

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['PATH_INFO'];
$api_version;
$body;
if($_SERVER && array_key_exists('PHP_AUTH_USER', $_SERVER)){
    $user_name = $_SERVER['PHP_AUTH_USER'];
$password = $_SERVER['PHP_AUTH_PW'];
    }


if( $_SERVER['PATH_INFO'] ){
    $temp = explode('/',$_SERVER['PATH_INFO']);
    if(count($temp) > 2){
        $api_version = $temp[1];
        $body = json_decode(file_get_contents('php://input'),true);
    }
}


$req = new MainController($api_version, $path, $method, $body);
$response = $req->getResponse();

header($response->getContentType());
http_response_code($response->getStatusCode());
echo $response->getBody();
