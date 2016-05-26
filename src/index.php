<?php
require_once __DIR__ . '/../db_info.php';
require_once "MainController.php";
require_once "v1/errors/InvalidJSONError.php";
require_once 'utils.php';

// Headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: accept, authorization, content-type");
header("Access-Control-Allow-Methods: PUT, DELETE, POST, GET, OPTIONS");

// Store request method
$method = $_SERVER['REQUEST_METHOD'];

// Store path
if (array_key_exists("PATH_INFO", $_SERVER)) {
    $path = explode('/', $_SERVER['PATH_INFO']);
    $path = trimPath($path, 1);
} else {
    $path = array();
}

// Store payload - JSON that is sent with the request
$payload = file_get_contents('php://input');
if ($payload) {
    $body = json_decode($payload, true);
} else {
    $body = array();
}

// If there are no errors in the JSON, instantiate MainController
if (json_last_error() == JSON_ERROR_NONE) {
    $req = new MainController($path, $method, $body);
    $response = $req->getResponse();
} else {
    $response = new ErrorResponse(new InvalidJSONError($payload));
}

// Set content type and response code for the result
header($response->getContentType());
http_response_code($response->getResponseCode());

// Print the JSON result of request
echo $response->getBody();
