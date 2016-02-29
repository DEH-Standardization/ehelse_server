<?php
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../main/controllers/DescriptionController.php';
require_once 'StandardVersionController.php';
require_once 'StandardFieldController.php';

class StandardController extends ResponseController
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


    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;




        if(count($path) >= 1 && $path[0] == 'fields'){
            $this->trimPath(1);
            $this->controller = new StandardFieldController($this->path,$this->method,$this->body);
        }
        elseif(count($path) == 1){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }elseif(count($path) >= 2){
            //check if number, if not return error
            if(is_numeric($path[0])){
                $this->id = $path[0];

                if($path[1] == 'versions'){
                    //send to StandardVersionController
                    $this->trimPath(2);
                    $this->controller = new StandardVersionController($this->path,$this->method,$this->body,$this->id);
                }else{
                    $this->controller = new DescriptionController();//return error
                }
            }else{
                $this->controller = new DescriptionController();//return error
            }
        }
    }



    protected function get()
    {
        return new Response("Return standard");
    }

    protected function update()
    {
        return  new Response("Standard, standard updated");
    }

    protected function delete()
    {
        return  new Response("Sd deleted");
    }

    protected function getAll()
    {
        return  new Response("return all std");
    }

    protected function create()
    {
        return  new Response("new standard");
    }


}