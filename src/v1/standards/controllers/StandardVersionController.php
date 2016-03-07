<?php

require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../dbmappers/StandardVersionDBMapper.php';

class StandardVersionController extends ResponseController
{

    public function __construct($path, $method, $body, $standard_id)
    {
        $this->path = $path;
        $this->method = $method;
        $this->body = $body;

        if(empty($this->path)){

        }elseif(is_numeric($this->path[0])){
            $this->id = $this->path[0];
        }else{
            $this->controller = new DescriptionController();
        }
    }


    protected function create()
    {
        return  new Response("create std version");
    }

    protected function getAll()
    {
        return  new Response("get std versions");
    }

    protected function get()
    {
        $response = array();
        $mapper = new StandardVersionDBMapper();
        $result = $mapper->getById($this->id);
        if ($result instanceof DBError) {
            return new ErrorResponse($result);
        }
        $standard_version = $result->toArray();
        $response['id'] = $standard_version['id'];
        $response['standardId'] = $standard_version['standardId'];
        $response['targetGroup'] = $this->getTargetGroups();
        $response['links'] = $this->getLinks();
        $response['fields'] = $this->getFields();

        return new Response(json_encode($response, JSON_PRETTY_PRINT));
    }

    private function getTargetGroups()
    {
        // TODO: return list fo all target groups
        return array();

    }
    private function getLinks()
    {
        // TODO: return list of all links
        return array();
    }
    private function getFields()
    {
        // TODO: return list of all links
        return array();
    }


    protected function update()
    {
        return  new Response("update std version");
    }

    protected function delete()
    {
        return  new Response("delete std version");
    }
}