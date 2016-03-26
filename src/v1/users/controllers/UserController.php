<?php

require_once __DIR__ . '/../../dbmappers/UserDBMapper.php';
require_once __DIR__ . '/../../errors/NotFoundError.php';
require_once __DIR__ . '/../../errors/AuthenticationError.php';
require_once __DIR__ . '/../../errors/AuthorizationError.php';
require_once __DIR__ . '/../../users/controllers/PasswordController.php';
require_once __DIR__ . '/../../users/controllers/LoginController.php';

class UserController extends ResponseController
{

    public function __construct($path, $method, $body)
    {
        $this->body = $body;
        $this->method=$method;
        $this->path = $path;
        if(count($this->path) != 0){
            if(count($this->path) == 1 && is_numeric($path[0])){
                $this->id = $path[0];
            }
            elseif(count($this->path) == 2 && is_numeric($path[0]) && $path[1] == "password"){
                $this->id = $path[0];
                $path = trimPath($path, 2);
                $this->controller = new PasswordController($path, $method, $body, $this->id);
            }
            elseif(count($this->path) == 1 && $path[0] == "login"){
                $this->id = $path[0];
                $path = trimPath($path, 1);
                $this->controller = new LoginController($path, $method, $body);
            }
            else{
                $this->controller = new ErrorController(new InvalidPathError());
            }
        }

        if(!$GLOBALS['CURRENT_USER']){
            $this->controller = new ErrorController(new AuthenticationError($this->method));
        }
    }

    protected static function getArrayFromObjectArray($array){
        $return_array = [];
        foreach($array as $item){
            array_push($return_array, $item->toArray());
        }
        return $return_array;
    }

    protected function getAll()
    {
        $mapper = new UserDBMapper();
        $users = $mapper->getAll();
        $users_array = UserController::getArrayFromObjectArray($users);

        $json = json_encode(array( "users" => $users_array), JSON_PRETTY_PRINT);

        return new Response($json);
    }

    protected function create()
    {
        $missing_fields = UserController::validateJSONFormat($this->body, User::REQUIRED_POST_FIELDS);
        if( !$missing_fields ){
            $mapper = new UserDBMapper();
            $user = User::fromJSON($this->body);
            $db_response = $mapper->add($user);

            if ($db_response instanceof DBError) {
                $response = new ErrorResponse($db_response);
            }
            elseif(is_numeric($db_response)){
                $this->id = $db_response;
                $response = $this->get();
            }
            else{
                //todo not sure how best to handle this
                throw new Exception("Not implemented error");
            }
        }
        else{
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }
        return $response;
    }

    protected function get()
    {
        $mapper = new UserDBMapper();
        $user = $mapper->getById($this->id);
        if($user){
            $response = new Response(json_encode($user->toArray(), JSON_PRETTY_PRINT));
        }
        else{
            $response = new ErrorResponse(new NotFoundError());
        }
        return $response;
    }

    protected function update()
    {
        if($GLOBALS['CURRENT_USER']->getId() == $this->id){
            $missing_fields = UserController::validateJSONFormat($this->body, User::REQUIRED_PUT_FIELDS);

            if( !$missing_fields ){
                $mapper = new UserDBMapper();
                $json = $this->body;
                $json["id"] = $this->id;
                $user=User::fromJSON($json);
                $db_response = $mapper->update($user);

                if ($db_response instanceof DBError) {
                    $response =  new ErrorResponse($db_response);
                }
                else{
                    $response=$this->get();
                }
            }
            else{
                $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
            }
            $this->controller = new ErrorController(new AuthenticationError($this->method));
        }
        else{
            $response = new ErrorResponse(new AuthorizationError($this->method));
        }

        return $response;
    }

    protected function delete()
    {
        // TODO: Implement delete() method.
        throw new Exception("Not implemented");
    }
}