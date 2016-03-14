<?php
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../main/controllers/DescriptionController.php';
require_once __DIR__.'/../../dbmappers/StandardDBMapper.php';
require_once 'StandardVersionController.php';
require_once 'StandardFieldController.php';
require_once __DIR__.'/../../models/Standard.php';
require_once __DIR__.'/../../errors/DBError.php';
require_once __DIR__.'/../../errors/MalformedJSONFormatError.php';

class StandardController extends ResponseController
{

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

    /**
     * Returns standard based on id
     * @return Response
     */
    protected function get()
    {
        $mapper = new StandardDBMapper();
        $response = $mapper->getStandardById($this->id);
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        return new Response($response->toJSON());
    }

    /**
     * Updates standard
     * @return Response
     */
    protected function update()
    {
        $mapper = new StandardDBMapper();
        $standard = Standard::fromArray($this->body);
        $response = $mapper->update($standard);
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
     * Returns all standards
     * @return Response
     */
    protected function getAll()
    {
        $mapper = new StandardDBMapper();
        $response = $mapper->getAllIds();
        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        $result = array('standards' => array());
        foreach ($response as $standard) {
            array_push($result['standards'], $standard->toArray());
        }
        return new Response(json_encode($result, JSON_PRETTY_PRINT));
    }

    protected function create()
    {
        $mapper = new StandardDBMapper();
        $missing_fields = StandardController::validateJSONFormat($this->body, Standard::REQUIRED_POST_FIELDS);

        if( empty( $missing_fields) ){
            $standard = Standard::fromArray($this->body);
            $response = $mapper->add($standard);

            if ($response instanceof DBError) {
                $response = new ErrorResponse($response);
            }
            else{
                $this->id = $response;
                $response =  $this->get();
            }
        }
        else{
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }

        return $response;
    }
}