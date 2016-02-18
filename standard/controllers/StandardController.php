<?php
require_once __DIR__ . '/../../iController.php';
require_once __DIR__ . '/../../main/controllers/DescriptionController.php';

class StandardController implements iController
{
    /*
     * Possibilities:
     *
     * .../standards
     * --method GET/POST
     *
     * .../standards/<id>
     * --return specific std
     *
     * .../standards/<id>/versions/...
     * --continue to version controller
     */

    private $method, $body, $path, $controller, $standard_id;

    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;

        if(count($path) == 1){
            //check if number, if not return error
            if(is_numeric($path[0]) && ltrim($path[0]) != ''){
                $this->standard_id = $path[0];
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }elseif(count($path) >= 2){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->standard_id = $path[0];

                if($path[1] == 'versions'){
                    //TODO if part 2 == versions, then make new instance of standardversionController, and send in path - what just checked
                    //   --> send in standard_id on that standard

                    echo 'created version controller';
                    $this->controller = 1; //REMOVE THIS, THIS FAKES A CTRL! This will cause fatal error!

                }else{
                    $this->controller = new DescriptionController();//return error
                }
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }
    }


    public function getResponse()
    {
        $response = null;

        if(!is_null($this->controller)){
            //got controller? if so, use its getResponse.
            $response = $this->controller->getResponse();

        }elseif(is_null($this->controller) && !is_null($this->standard_id)){
            //TODO if not controller, but standard_id, then return the json for THAT standard.
            $response = 'return specific std with given id';

        }else{
            //TODO if neither, return a list with all standards.
            //if method == GET: return ALL standards
            //if method == POST: create new standard using body
            if($this->method == 'POST'){
                $response = 'I created a new std!';
            }elseif($this->method == 'GET'){
                $response = 'I returned all the stds!';
            }
        }
        return $response;
        //return json_encode($this->body);
    }
}