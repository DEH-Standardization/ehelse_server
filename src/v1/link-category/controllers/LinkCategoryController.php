<?php

require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../errors/InvalidPathError.php';
require_once __DIR__.'/../../errors/ErrorController.php';
require_once __DIR__.'/../../dbmappers/LinkCategoryDBMapper.php';
require_once __DIR__.'/../../models/LinkCategory.php';

class LinkCategoryController extends ResponseController
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
        $mapper = new LinkCategoryDBMapper();
        $link_categories = $mapper->getAll();
        $link_category_array = [];
        foreach($link_categories as $link_category) {
            array_push($link_category_array, $link_category->toArray());
        }
        $json = json_encode(array( "LinkCategories" => $link_category_array), JSON_PRETTY_PRINT);

        return new Response($json);

    }

    protected function create()
    {
        // TODO: Implement create() method.
    }

    protected function get()
    {
        $response = null;
        $mapper = new LinkCategoryDBMapper();
        $link_category = $mapper->getById($this->id);
        if($link_category) {
            $response = new Response($link_category->toJSON());

        } else {
            $response = new ErrorResponse(new NotFoundError());
        }
        return $response;
    }

    protected function update()
    {
        // TODO: Implement update() method.
    }

    protected function delete()
    {
        // TODO: Implement delete() method.
    }
}