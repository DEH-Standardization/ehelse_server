<?php
require_once __DIR__ . '/../../responses/ResponseController.php';
require_once __DIR__ . '/../../main/controllers/DescriptionController.php';
require_once __DIR__ . '/../../dbmappers/ProfileDBMapper.php';
require_once 'ProfileVersionController.php';
require_once 'ProfileFieldController.php';

class ProfileController extends ResponseController
{
    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;

        if(count($path) >= 1 && $path[0] == 'fields'){
            $this->trimPath(1);
            $this->controller = new ProfileFieldController($this->path,$this->method,$this->body);
        }
        elseif(count($path) == 1){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new ErrorController(new InvalidPathError());
            }
        }elseif(count($path) >= 2){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];

                if($path[1] == 'versions'){
                    //send to ProfileVersionController
                    $this->trimPath(2);
                    $this->controller = new ProfileVersionController($this->path,$this->method,$this->body,$this->id);
                }else{
                    $this->controller = new DescriptionController();//return error
                }
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }
    }

    /**
     * Returns profile based on id
     * @return Response
     */
    protected function get()
    {
        $mapper = new ProfileDBMapper();
        $response = $mapper->getProfileById($this->id);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        $json = $response->toJSON();
        return new Response($json);
    }

    /**
     * Updates profile
     * @return Response
     */
    protected function update()
    {
        $mapper = new ProfileDBMapper();
        $assoc = $this->body;
        $profile = new Profile(
            $assoc['id'],
            $assoc['timestamp'],
            $assoc['title'],
            $assoc['description'],
            $assoc['is_in_catalog'],
            $assoc['sequence'],
            $assoc['topic_id']);
        $response = $mapper->update($profile);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return $this->get();
    }

    protected function delete()
    {
        // TODO must find a solution to deleting
        return  new Response("Sd deleted");
    }

    /**
     * Returns all profiles
     * @return Response
     */
    protected function getAll()
    {
        $mapper = new ProfileDBMapper();
        $response = $mapper->getAllIds();
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        $result = array('profiles' => array());
        foreach ($response as $profile) {
            array_push($result['profiles'], $profile->toArray());
        }
        return new Response(json_encode($result, JSON_PRETTY_PRINT));
    }

    protected function create()
    {
        $mapper = new ProfileDBMapper();
        $assoc = $this->body;
        $profile = new Profile(
            $assoc['id'],
            $assoc['timestamp'],
            $assoc['title'],
            $assoc['description'],
            $assoc['is_in_catalog'],
            $assoc['sequence'],
            $assoc['topic_id']);
        $response = $mapper->add($profile);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return $this->get();
    }
}