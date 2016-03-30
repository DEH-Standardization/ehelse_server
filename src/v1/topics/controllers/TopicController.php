<?php
require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../errors/InvalidPathError.php';
require_once __DIR__.'/../../errors/ErrorController.php';
require_once __DIR__.'/../../dbmappers/TopicDBMapper.php';
require_once __DIR__.'/../../models/Document.php';

class TopicController extends ResponseController
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

    /**
     * Function retrieving all topics in a structure representing the parent - child structure.
     * @return Response
     */
    protected function getAll()
    {
        $mapper = new TopicDbMapper();
        $topics = $mapper->getTopicsAsThree();
        $topics_array = [];
        foreach($topics as $topic){
            array_push($topics_array, $topic->toArray());
        }

        $json = json_encode(array( "topics" => $topics_array), JSON_PRETTY_PRINT);

        return new Response($json);
    }

    /**
     * Function creating a new topic.
     * @return Response
     */
    protected function create()
    {
        $missing_fields = TopicController::validateJSONFormat($this->body, Topic::REQUIRED_POST_FIELDS);
        if( !$missing_fields ){
            $mapper = new TopicDbMapper();
            $assoc = $this->body;
            $topic = new Topic(
                null,
                null,
                $assoc['title'],
                $assoc['description'],
                $assoc['sequence'],
                $assoc['parent'],
                $assoc['comment']);
            $response = $mapper->add($topic);

            if ($response instanceof DBError) {
                $response = new ErrorResponse($response);
            }
            $this->id = $response;
            $response = $this->get();
        }
        else{
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }
        return $response;
    }

    /**
     * Function retrieving a topic with it's documents.
     * @return Response
     */
    protected function get()
    {
        $result = $this->getChildren($this->id);
        return new Response(json_encode($result, JSON_PRETTY_PRINT));
    }

    private function getChildren($id)
    {
        $controller = new TopicDbMapper();
        $topic = $controller->getTopicById($id);
        $result = $topic->toArray();
        $topic_children = $controller->getSubtopicsByTopicId($id);

        $children = array();
        foreach ($topic_children as $child) {

            if (count($topic_children) > 0) {
                array_push($children, $this->getChildren($child->getId()));
            } else {
                array_push($children, $child->toArray());
            }
        }
        $result['children'] = $children;


        $documents = array();
   
        usort($documents, function ($a, $b)
        {
            return $a['sequence'] - $b['sequence'];

        });
        $result['documents'] = array_merge($result['documents'],$documents);
        return $result;
    }

    /**
     * Function updating a topics values.
     * @return Response
     */
    protected function update()
    {
        return new ErrorResponse(new MethodNotAllowedError($this->method));
    }

    /**
     * Function deleting a topic.
     * @return Response
     */
    protected function delete()
    {
        // TODO fix delete
        return new Response("delete topic");
    }
}
