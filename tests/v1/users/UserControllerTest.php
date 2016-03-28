<?php

require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/users/controllers/UserController.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthenticationError.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthorizationError.php";


class UserControllerTest extends EHelseDatabaseTestCase
{
    public function test__get_users_without_authenticating_returns_AuthenticationError()
    {
        $controller = new UserController([],Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertTrue(get_class($response) == ErrorResponse::class);
        self::assertTrue(get_class($response->getError()) == AuthenticationError::class);
    }

    public function test__get_user_without_authenticating_returns_AuthenticationError()
    {
        $controller = new UserController(['1'],Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertTrue(get_class($response) == ErrorResponse::class);
        self::assertTrue(get_class($response->getError()) == AuthenticationError::class);
    }

    public function test__get_users_when_authenticating_returns_User()
    {
        User::login(User::byEmail('marius'));
        $controller = new UserController([],Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertTrue(get_class($response) == Response::class);
    }

    public function test__get_user_when_authenticating_returns_User()
    {
        User::login(User::byEmail('marius'));
        $controller = new UserController(['1'],Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertTrue(get_class($response) == Response::class);
    }


}