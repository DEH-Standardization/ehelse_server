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
        // TODO: Implement create() method.
        throw new Exception("Not implemented error");
    }

    protected function get()
    {
        // TODO: Implement get() method.
        throw new Exception("Not implemented error");
    }

    protected function update()
    {
        // TODO: Implement update() method.
        throw new Exception("Not implemented error");
    }

    protected function delete()
    {
        // TODO: Implement delete() method.
        throw new Exception("Not implemented error");
    }
}