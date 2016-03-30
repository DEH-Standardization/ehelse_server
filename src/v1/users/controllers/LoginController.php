<?php

require_once __DIR__ . '/../../iController.php';
class LoginController implements iController
{
    protected $path, $method, $body;

    public function __construct($path, $method, $body)
    {
        $this->path=$path;
        $this->method=$method;
        $this->body=$body;
    }

    public function getResponse(){
        $response = null;
        if( empty($this->path) ){
            if($this->method == Response::REQUEST_METHOD_GET){
                if($GLOBALS['CURRENT_USER']){
                    $response = new Response(
                        json_encode(
                            $GLOBALS['CURRENT_USER']->toArray(),
                            JSON_PRETTY_PRINT));
                }
                else{
                    $response = new ErrorResponse(new AuthenticationError($this->method));
                }
            }
            elseif($this->method == Response::REQUEST_METHOD_OPTIONS){
                $response = new Response("{}");
            }
            else{
                $response = new ErrorResponse(new MethodNotAllowedError($this->method));
            }
        }
        else{
            $response = new ErrorResponse(new InvalidPathError());
        }
        return $response;
    }
}