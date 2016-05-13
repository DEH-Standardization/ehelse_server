<?php

require_once 'v1/iController.php';
require_once 'v1/APIV1Controller.php';
require_once 'utils.php';

class MainController implements iController
{
    private $controller, $api_version, $path;

    /**
     * MainController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->path = $path;
        if (count($path)) {
            $this->api_version = $path[0];
        } else {
            $this->api_version = "";
        }

        // Check if the url ended with '/', if se delete
        if (end($path) == '') {
            $ak = array_keys($path);
            unset($path[end($ak)]);
        }
        $path = trimPath($path, 1);

        // Authenticate user
        $this->authenticateUser();

        $requires_authentication = true;

        // GET, OPTIONS and POST to '/users/reset-password' does not require authentication
        if ($method == Response::REQUEST_METHOD_GET || $method == Response::REQUEST_METHOD_OPTIONS ||
            ($method == Response::REQUEST_METHOD_POST && $path[0] == 'users' && $path[1] == 'reset-password')
        ) {
            if ($method == Response::REQUEST_METHOD_GET && $path[0] == 'users') {
                $requires_authentication = true;
            } else {
                $requires_authentication = false;
            }
        }

        // If user is not set and method requires authentication, returns AuthenticationError
        if (!array_key_exists('CURRENT_USER', $GLOBALS) && $requires_authentication) {
            $this->controller = new ErrorController(new AuthenticationError($method));
        } else {
            // Chose API version
            switch ($this->api_version) {
                case 'v1':
                    $this->controller = new APIV1Controller($path, $method, $body);
                    break;
                default:
                    $this->controller = new ErrorController(new InvalidPathError());
                    break;
            }
        }
    }

    /**
     * Authenticates user
     */
    public function authenticateUser()
    {
        if ($_SERVER && array_key_exists('PHP_AUTH_USER', $_SERVER)) {
            $email = $_SERVER['PHP_AUTH_USER'];
            $password = $_SERVER['PHP_AUTH_PW'];
            $user = User::authenticate($email, $password);
            if ($user) {
                define("AUTHENTICATED", true);
                User::login($user);
            } else {
                define("AUTHENTICATED", false);
            }
        } else {
            define("AUTHENTICATED", false);
        }
    }

    /**
     * Returns response from controller
     * @return ErrorResponse
     */
    public function getResponse()
    {
        return $this->controller->getResponse();
    }
}