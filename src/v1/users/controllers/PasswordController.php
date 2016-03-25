<?php


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
        $this->path=$path;
        $this->body=$body;
        $this->method=$method;
        $this->id=$id;
        if( count($this->path) != 0){
            print_r($this->path);
            $this->controller = new ErrorController(new InvalidPathError());
        }
    }



    protected function getAll()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    protected function create()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    protected function get()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    protected function update()
    {
        $missing_fields = UserController::validateJSONFormat($this->body, User::REQUIRED_PASSWORD_PUT_FIELD);

        if( !$missing_fields ){

            $mapper = new UserDBMapper();
            $json = $this->body;
            $user=User::fromResetPasswordQuery($this->id, $json);
            if($user){


                $db_response = $mapper->resetPassword($user);

                if ($db_response instanceof DBError) {

                    $response =  new ErrorResponse($db_response);
                }
                else{
                    $user = $mapper->getById($this->id);
                    if($user){
                        $response = new Response(json_encode($user->toArray(), JSON_PRETTY_PRINT));
                    }
                    else{
                        $response = new ErrorResponse(new NotFoundError());
                    }
                    return $response;
                }
            }
            else{
                $response = new ErrorResponse(new ApplicationError("Reset password error","There was a problem with the password"));
            }
        }
        else{
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }
        return $response;
    }

    protected function delete()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }
}