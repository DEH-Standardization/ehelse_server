<?php

require_once __DIR__ . "/../EHelseDatabaseTestCase.php";
require_once __DIR__ . "/../EHelseTestCase.php";
require_once __DIR__ . "/../../../src/v1/users/controllers/UserController.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthenticationError.php";
require_once __DIR__ . "/../../../src/v1/errors/AuthorizationError.php";


class UserControllerTest extends EHelseDatabaseTestCase
{
    const list_name = "users";
    const fields = ["id", "name", "email"];

    /**
    * Test all users empty db
    */
    public function test_get_users_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_user_table.xml");
        $controller = new UserController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

    /**
     * Test get user empty db
     */
    public function test_get_user_on_empty_table_returns_empty_array()
    {
        $this->mySetup(__DIR__ . "/empty_user_table.xml");
        $controller = new UserController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidErrorResponse($response, Response::STATUS_CODE_NOT_FOUND);
    }

    /**
     * Test get all users
     */
    public function test_get_all_users_on_basic_table_returns_users()
    {
        $this->mySetup(__DIR__ . "/basic_user_table.xml");
        $controller = new UserController([], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponseList($response);
    }

    /**
     * Test get user
     */
    public function test_get_user_on_basic_table_returns_user()
    {
        $this->mySetup(__DIR__ . "/basic_user_table.xml");
        $controller = new UserController([1], Response::REQUEST_METHOD_GET, array());
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);
    }

    /**
     * Test delete user
     */
    public function test_delete_user_returns()
    {
        $this->mySetup(__DIR__ . "/basic_user_table.xml");
        $controller = new UserController([1], Response::REQUEST_METHOD_DELETE, array());
        $response = $controller->getResponse();
        
        print_r($response);
        
        self::assertIsValidDeleteResponse($response);
    }

    /**
     * Test put document field
     */
    public function test_update_user_updated_user()
    {
        $this->mySetup(__DIR__ . "/basic_user_table.xml");
        $new_data = [
            "id" => 1,
            "name" => "Gunnar Olsen",
            "email" => "gunnarolsen@lllabcdgg.com"
        ];
        $controller = new UserController([1], Response::REQUEST_METHOD_PUT, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /**
     * Test put user/password
     */
    public function test_post_user_password_creates_user()
    {
        $this->mySetup(__DIR__ . "/basic_user_table.xml");
        $new_data = [
            "password" => "Password1234"
        ];
        $controller = new PasswordController([], Response::REQUEST_METHOD_PUT, $new_data, 1);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        print_r($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /**
     * Test post user
     */
    public function test_post_user_creates_user()
    {
        $this->mySetup(__DIR__ . "/basic_user_table.xml");
        $new_data = [
            "name" => "Gunnar Olsen",
            "email" => "gunnarolsen@lllabcdgg.com"
        ];
        $controller = new UserController([], Response::REQUEST_METHOD_POST, $new_data);
        $response = $controller->getResponse();
        self::assertIsValidResponse($response, Response::STATUS_CODE_CREATED);

        print_r($response);

        self::assertIsCorrectResponseData($response->getBody(), $new_data);
    }

    /*
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
*/

}