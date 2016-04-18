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
        $missing_fields = ResponseController::validateJSONFormat($this->body, LinkCategory::REQUIRED_POST_FIELDS);
        if( !$missing_fields ) {
            $mapper = new LinkCategoryDBMapper();
            $json = $this->body;
            $json['id'] = null;
            $link_category = LinkCategory::fromJSON($json);
            $db_response = $mapper->add($link_category);

            if ($db_response instanceof DBError) {
                $response = new ErrorResponse($db_response);
            } elseif (is_numeric($db_response)) {
                $this->id = $db_response;
                $response = $this->get();
            } else {
                //todo not sure how best to handle this
                throw new Exception("Not implemented error");
            }
        } else {
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }
        return $response;
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
        $missing_fields = ResponseController::validateJSONFormat($this->body, LinkCategory::REQUIRED_PUT_FIELDS);

        if( !$missing_fields ){
            $mapper = new LinkCategoryDBMapper();
            $json = $this->body;
            $json["id"] = $this->id;
            $link_category = LinkCategory::fromJSON($json);
            $db_response = $mapper->update($link_category);

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

    protected function delete()
    {
        $mapper = new LinkCategoryDBMapper();
        return new Response(json_encode($mapper->deleteById($this->id), JSON_PRETTY_PRINT));
    }
}