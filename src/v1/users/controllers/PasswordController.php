<?php

require_once __DIR__ . '/../../responses/ResponseController.php';

class PasswordController extends ResponseController
{

    /**
     * PasswordController constructor.
     * @param array $path
     * @param $method
     * @param $body
     * @param $id
     */
    public function __construct($path, $method, $body, $id)
    {
        $this->path = $path;
        $this->body = $body;
        $this->method = $method;
        $this->id = $id;
        if (count($this->path) != 0) {
            $this->controller = new ErrorController(new InvalidPathError());
        }
    }

    /**
     * GetAll not allowed - returns MethodNotAllowedError
     * @return ErrorResponse
     */
    protected function getAll()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    // Create not allowed - returns MethodNotAllowedError
    protected function create()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    /**
     * Get not allowed - returns MethodNotAllowedError
     * @return ErrorResponse
     */
    protected function get()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    /**
     * Updates password in database
     * @return ErrorResponse|Response
     */
    protected function update()
    {
        $missing_fields = UserController::validateJSONFormat($this->body, User::REQUIRED_PASSWORD_PUT_FIELD);

        // Check that required fields are not missing
        if (!$missing_fields) {
            $mapper = new UserDBMapper();
            $json = $this->body;
            $user = User::fromResetPasswordQuery($this->id, $json);

            // If user is set
            if ($user) {
                $db_response = $mapper->resetPassword($user);

                if ($db_response instanceof DBError) {
                    $response = new ErrorResponse($db_response);
                } else {
                    $user = $mapper->getById($this->id);
                    if ($user) {
                        $response = new Response(json_encode($user->toArray(), JSON_PRETTY_PRINT), Response::STATUS_CODE_CREATED);
                    } else {
                        $response = new ErrorResponse(new NotFoundError());
                    }
                    return $response;
                }
            } else {
                $response = new ErrorResponse(new ApplicationError(
                        "Reset password error",
                        "There was a problem with the password")
                );
            }
        } else {
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }
        return $response;
    }

    /**
     * Delete not allowed - returns MethodNotAllowedError
     * @return ErrorResponse
     */
    protected function delete()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }
}