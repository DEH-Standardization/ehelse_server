<?php
require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../errors/InvalidPathError.php';
require_once __DIR__.'/../../errors/ErrorController.php';
require_once __DIR__.'/../../dbmappers/TargetGroupDBMapper.php';
require_once __DIR__.'/../../models/TargetGroup.php';

class TargetGroupController extends ResponseController
{
    /**
     * TopicController constructor.
     * @param $path array Array containing the remaining path after v1/topics/ has been removed
     * @param $method string HTTP Request method
     * @param $body array Array containing request body
     */
    public function __construct($path, $method, $body)
    {
        $this->method = $method;
        $this->body = $body;
        $this->path = $path;
        $this->db_mapper = 'TargetGroupDBMapper';

        if(count($this->path) != 0){
            if(count($this->path) == 1 && is_numeric($path[0])){
                $this->id = $path[0];
            }else{
                $this->controller = new ErrorController(new InvalidPathError());
            }
        }
    }

    protected function getAll()
    {
        $mapper = new TargetGroupDBMapper();
        $target_groups = $mapper->getAll();
        $target_groups_array = [];
        foreach($target_groups as $target_group){
            array_push($target_groups_array, $target_group->toArray());
        }

        $json = json_encode(array( "targetGroups" => $target_groups_array), JSON_PRETTY_PRINT);

        return new Response($json);
    }

    protected function create()
    {
        $missing_fields = TargetGroupController::validateJSONFormat($this->body, TargetGroup::REQUIRED_POST_FIELDS);
        if( !$missing_fields ){
            $mapper = new TargetGroupDBMapper();
            $target_group = TargetGroup::fromJSON($this->body);
            $db_response = $mapper->add($target_group);

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
        $mapper = new TargetGroupDBMapper();
        $target_group = $mapper->getById($this->id);
        if($target_group){
            $response = new Response(json_encode($target_group->toArray(), JSON_PRETTY_PRINT));
        }
        else{
            $response = new ErrorResponse(new NotFoundError());
        }
        return $response;
    }

    protected function update()
    {
        $missing_fields = TargetGroupController::validateJSONFormat($this->body, TargetGroup::REQUIRED_PUT_FIELDS);

        if( !$missing_fields ){
            $mapper = new TargetGroupDBMapper();
            $json = $this->body;
            $json["id"] = $this->id;
            $target_group=TargetGroup::fromJSON($json);
            $db_response = $mapper->update($target_group);

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
        return $response;

    }

}