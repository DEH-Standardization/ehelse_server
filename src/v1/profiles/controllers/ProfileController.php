<?php
require_once __DIR__ . '/../../iController.php';
require_once __DIR__ . '/../../main/controllers/DescriptionController.php';
require_once 'ProfileVersionController.php';
require_once 'ProfileFieldController.php';

class ProfileController implements iController
{

    private $method, $body, $path, $controller, $profile_id;

    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;

        if(count($path) >= 1 && $path[0] == 'fields'){
            echo print_r($path);
            $this->trimPath(1);
            $this->controller = new ProfileFieldController($this->path,$this->method,$this->body);
        }
        elseif(count($path) == 1){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->profile_id = $path[0];
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }elseif(count($path) >= 2){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->profile_id = $path[0];

                if($path[1] == 'versions'){
                    //send to ProfileVersionController
                    $this->trimPath(2);
                    $this->controller = new ProfileVersionController($this->path,$this->method,$this->body,$this->profile_id);
                }else{
                    $this->controller = new DescriptionController();//return error
                }
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }
    }

    //if $parts == 1,then remove profile_id, if $parts == 2, then remove profile_id AND 'versions'
    private function trimPath($parts){
        if($parts == 1){
            //remove part from path array and fix index of array
            unset($this->path[0]);
            $this->path = array_values($this->path);

        }elseif($parts == 2){
            //remove part from path array and fix index of array
            unset($this->path[0]);
            unset($this->path[1]);
            $this->path = array_values($this->path);
        }
    }

    private function getProfile()
    {
        return "Return profile";
    }

    private function updateProfile()
    {
        return "Profile, profile updated";
    }

    private function deleteProfile()
    {
        return "Profile deleted";
    }

    private function handleProfileRequest()
    {
        $response = null;
        switch($this->method){
            case "GET":
                $response = $this->getProfile();
                break;
            case "POST":
                $response = "Error not supposed to post here";
                break;
            case "PUT":
                $response = $this->updateProfile();
                break;
            case "DELETE":
                $response = $this->deleteProfile();
                break;
        }
        return $response;
    }

    public function getResponse()
    {
        $response = null;

        if(!is_null($this->controller)){
            //got controller? if so, use its getResponse.
            $response = $this->controller->getResponse();

        }elseif(is_null($this->controller) && !is_null($this->profile_id)){
            //TODO if not controller, but profile_id, then return the json for THAT profile.
            $response = $this->handleProfileRequest();

        }else{
            //TODO if neither, return a list with all profiles.
            //if method == GET: return ALL profiles
            //if method == POST: create new profile using body
            if($this->method == 'POST'){
                $response = 'I created a new profile!';
            }elseif($this->method == 'GET'){
                $response = 'I returned all the profiles!';
            }
        }
        return $response;
    }
}