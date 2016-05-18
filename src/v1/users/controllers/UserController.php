<?php

require_once __DIR__ . '/../../dbmappers/UserDBMapper.php';
require_once __DIR__ . '/../../errors/NotFoundError.php';
require_once __DIR__ . '/../../errors/ErrorController.php';
require_once __DIR__ . '/../../errors/AuthenticationError.php';
require_once __DIR__ . '/../../errors/AuthorizationError.php';
require_once __DIR__ . '/../../errors/DuplicateUserError.php';
require_once __DIR__ . '/../../users/controllers/PasswordController.php';
require_once __DIR__ . '/../../users/controllers/LoginController.php';
require_once __DIR__ . '/../../users/controllers/ResetPasswordController.php';

class UserController extends ResponseController
{
    /**
     * UserController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->body = $body;
        $this->method = $method;
        $this->path = $path;
        if (count($this->path) != 0) {
            if (count($this->path) == 1 && is_numeric($path[0])) {
                $this->id = $path[0];
            } elseif (count($this->path) == 2 && is_numeric($path[0]) && $path[1] == "password") {
                $this->id = $path[0];
                $path = trimPath($path, 2);
                $this->controller = new PasswordController($path, $method, $body, $this->id);
            } elseif (count($this->path) == 1 && $path[0] == "reset-password") {
                $this->id = $path[0];
                $path = trimPath($path, 2);
                $this->controller = new ResetPasswordController($path, $method, $body);
            } elseif (count($this->path) == 1 && $path[0] == "login") {
                $this->id = $path[0];
                $path = trimPath($path, 1);
                $this->controller = new LoginController($path, $method, $body);
            } else {
                $this->controller = new ErrorController(new InvalidPathError());
            }
        }
    }

    /**
     * Returns array of array representation of the items
     * @param $items
     * @return array
     */
    protected static function getArrayFromObjectArray($items)
    {
        $return_array = [];
        foreach ($items as $item) {
            array_push($return_array, $item->toArray());
        }
        return $return_array;
    }

    protected function getAll()
    {
        $mapper = new UserDBMapper();
        $users = $mapper->getAll();
        $users_array = UserController::getArrayFromObjectArray($users);

        $json = json_encode(array("users" => $users_array), JSON_PRETTY_PRINT);

        return new Response($json);
    }

    /**
     * Returns if an email address exists in database
     * @param $email
     * @return bool
     */
    private function isValidEmail($email)
    {
        $mapper = new UserDBMapper();
        $result = $mapper->getByEmail($email);

        if ($result instanceof DBError || $result == null) {   // check that email exists
            return true;
        }
        return false;
    }

    /**
     *
     * @return ErrorResponse|Response
     * @throws Exception
     */
    protected function create()
    {
        $missing_fields = UserController::validateJSONFormat($this->body, User::REQUIRED_POST_FIELDS);

        // Check that required fields are not missing
        if (!$missing_fields) {
            $mapper = new UserDBMapper();
            $user = User::fromJSON($this->body);

            if ($this->isValidEmail($user->getEmail())) {
                $db_response = $mapper->add($user);

                if ($db_response instanceof DBError) {
                    $response = new ErrorResponse($db_response);
                }
                // If db_response is numeric, sets id, and sets new, random password to this user
                elseif (is_numeric($db_response)) {
                    $this->id = $db_response;
                    ResetPasswordController::setNewPassword(
                        $this->body, EmailSender::REGISTER_EMAIL);  // Set random password and notify user by email
                    $response = $this->get();
                } else {
                    //todo not sure how best to handle this
                    throw new Exception("Not implemented error");
                }
            } else {
                $response = new ErrorResponse(new DuplicateUserError());
            }
        } else {
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }
        return $response;
    }

    protected function get()
    {
        $mapper = new UserDBMapper();
        $user = $mapper->getById($this->id);
        if ($user) {
            $response = new Response(json_encode($user->toArray(), JSON_PRETTY_PRINT));
        } else {
            $response = new ErrorResponse(new NotFoundError());
        }
        return $response;
    }

    protected function update()
    {
        $missing_fields = UserController::validateJSONFormat($this->body, User::REQUIRED_PUT_FIELDS);

        // Check that required fields are not missing
        if (!$missing_fields) {
            $mapper = new UserDBMapper();
            $json = $this->body;
            $json["id"] = $this->id;
            $user = User::fromJSON($json);
            $db_response = $mapper->update($user);

            if ($db_response instanceof DBError) {
                $response = new ErrorResponse($db_response);
            } else {
                $response = $this->get();
            }
        } else {
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }
        $this->controller = new ErrorController(new AuthenticationError($this->method));

        return $response;
    }

    /**
     * Deletes user
     * @return Response
     */
    protected function delete()
    {
        $user_db_mapper = new UserDBMapper();
        return new Response(json_encode($user_db_mapper->deleteById($this->id), JSON_PRETTY_PRINT), Response::STATUS_CODE_NO_CONTENT);
    }
}