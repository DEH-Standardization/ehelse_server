<?php
require_once "MainController.php";
require_once "v1/errors/InvalidJSONError.php";
require_once 'utils.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: accept, authorization, content-type");

$method = $_SERVER['REQUEST_METHOD'];
$path = explode('/',$_SERVER['PATH_INFO']);
$path = trimPath($path, 1);

$payload = file_get_contents('php://input');

$body = json_decode($payload,true);

if( json_last_error() == JSON_ERROR_NONE ){
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
}
else{
    $response = new ErrorResponse(new InvalidJSONError($payload));
}

header($response->getContentType());
http_response_code($response->getResponseCode());
echo $response->getBody();
