<?php

require_once __DIR__ . '/../../iController.php';

class LoginController implements iController
{
    protected $path, $method, $body;

    /**
     * LoginController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;
    }

    /**
     * Returns response
     * @return ErrorResponse|null|Response
     */
    public function getResponse()
    {
        $response = null;
        if (empty($this->path)) {
            // If request method = GET and CURRENT_USER is set, return JSON representation of user
            if ($this->method == Response::REQUEST_METHOD_GET) {
                if ($GLOBALS['CURRENT_USER']) {
                    $response = new Response(
                        json_encode(
                            $GLOBALS['CURRENT_USER']->toArray(),
                            JSON_PRETTY_PRINT));
                } else {
                    $response = new ErrorResponse(new AuthenticationError($this->method));
                }

            }
            // Else if request method = OPTIONS, return "{}"
            elseif ($this->method == Response::REQUEST_METHOD_OPTIONS) {
                $response = new Response("{}");
            }
            // Other request methods are not allowed
            else {
                $response = new ErrorResponse(new MethodNotAllowedError($this->method));
            }
        } else {
            $response = new ErrorResponse(new InvalidPathError());
        }
        return $response;
    }
}