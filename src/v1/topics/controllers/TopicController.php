<?php
require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../errors/InvalidPathError.php';
require_once __DIR__.'/../../errors/ErrorController.php';
require_once __DIR__.'/../../dbmappers/TopicDBMapper.php';
require_once __DIR__.'/../../models/Document.php';
require_once __DIR__.'/../../errors/CantDeleteError.php';

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
            $array = $topic->toArray();
            //$array['documents'] = $this->getDocuments($topic->getId()); // Adds all documents
            array_push($topics_array, $array);
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
                $assoc['parentId'],
                $assoc['comment']);
            $response = $mapper->add($topic);


            if ($response instanceof DBError) {
                $response = new ErrorResponse($response);
            }
            else{
                $this->id = $response;
                $response = $this->get();
            }
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
        if($result){

            return new Response(json_encode($result, JSON_PRETTY_PRINT));
        }
        else{
            return new ErrorResponse(new NotFoundError());
        }
    }

    private function getChildren($id)
    {
        $controller = new TopicDbMapper();
        $topic = $controller->getTopicById($id);
        if($topic){
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

            $result['documents'] = array_merge($result['documents'],$this->getDocuments($id));
            return $result;
        }
    }

    /**
     * Returns all documents for the topic
     * @param $topic_id
     * @return array
     */
    private function getDocuments($topic_id)
    {
        $document_mapper = new DocumentDBMapper();
        $documents = $document_mapper->getDocumentsByTopicId($topic_id);

        $documents_array = array();
        foreach ($documents as $document) {
            $document->setTargetGroups(DocumentController::getTargetGroups($document));
            $document->setLinks(DocumentController::getLinks($document));
            $document->setFields(DocumentController::getFields($document));

            $document_array = $document->toArray();
            array_push($documents_array, $document_array);
        }

        usort($documents_array, function ($a, $b)   // Sort document list on sequence
        {
            return $a['sequence'] - $b['sequence'];
        });

        return $documents_array;
    }

        /**
         * Function updating a topics values.
         * @return Response
         */
        protected function update()
        {
            $response = null;
            $topic_mapper = new TopicDbMapper();
            $topic = Topic::fromJSON($this->body);
            $result = $topic_mapper->add($topic);
            if (!$result instanceof DBError) {
                return $this->get();
            } else {
                $response = $result->toJSON();
            }
            return new Response($response);
        }

    /**
     * Function deleting a topic.
     * @return Response
     */
    protected function delete()
    {
        $mapper = new TopicDbMapper();

        if ( !( $mapper->hasSubtopic($this->id) || $mapper->hasDocuments($this->id)))
            return new Response($mapper->deleteById($this->id), Response::STATUS_CODE_NO_CONTENT);
        else
            return new ErrorResponse(new CantDeleteError());
    }


}
