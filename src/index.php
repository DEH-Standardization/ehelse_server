<?php
require_once "MainController.php";
require_once "v1/errors/InvalidJSONError.php";
require_once 'utils.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: accept, authorization, content-type");
header("Access-Control-Allow-Methods: PUT, DELETE, POST, GET, OPTIONS");

$method = $_SERVER['REQUEST_METHOD'];
if (array_key_exists("PATH_INFO", $_SERVER)) {
    $path = explode('/', $_SERVER['PATH_INFO']);
    $path = trimPath($path, 1);
} else {
    $path = array();
}

$payload = file_get_contents('php://input');
if ($payload) {
    $body = json_decode($payload, true);
} else {
    $body = array();
}


if (json_last_error() == JSON_ERROR_NONE) {
    $req = new MainController($path, $method, $body);
    $response = $req->getResponse();
} else {
    $response = new ErrorResponse(new InvalidJSONError($payload));
}

header($response->getContentType());
http_response_code($response->getResponseCode());
echo $response->getBody();
