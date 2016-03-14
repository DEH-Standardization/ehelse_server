<?php
require_once "MainController.php";

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: accept, authorization, content-type");

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/',$_SERVER['PATH_INFO']);
$body = json_decode(file_get_contents('php://input'),true);

if($_SERVER && array_key_exists('PHP_AUTH_USER', $_SERVER)){
    $user_name = $_SERVER['PHP_AUTH_USER'];
    $password = $_SERVER['PHP_AUTH_PW'];
    if($user_name == $password) { //TODO: add method to authenticate user
        define("AUTHENTICATED", true);
    }
    else{
        define("AUTHENTICATED", false);
    }
}
else{
    define("AUTHENTICATED", false);
}



$req = new MainController($path, $method, $body);
$response = $req->getResponse();

header($response->getContentType());
http_response_code($response->getResponseCode());
echo $response->getBody();
