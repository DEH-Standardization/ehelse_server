<?php
require_once __DIR__.'/../../responses/Response.php';
require_once __DIR__.'/../../responses/ResponseController.php';
require_once __DIR__.'/../../errors/InvalidPathError.php';
require_once __DIR__.'/../../errors/ErrorController.php';
require_once __DIR__.'/../../main/controllers/DescriptionController.php';
require_once __DIR__.'/../../dbmappers/TopicDBMapper.php';
require_once __DIR__.'/../../models/Standard.php';
require_once __DIR__.'/../../models/Profile.php';


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
        return new Response("all topics");
    }

    /**
     * Function creating a new topic.
     * @return Response
     */
    protected function create()
    {
        $mapper = new TopicDbMapper();
        $assoc = $this->body;
        $topic = new Topic(null, null,
            $assoc['title'],
            $assoc['description'],
            $assoc['number'],
            $assoc['is_in_catalog'],
            $assoc['sequence'],
            $assoc['parent']);
        $response = $mapper->add($topic);

        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }

        $result =  $mapper->getTopicById($response)->toArray();
        return new Response(json_encode($result, JSON_PRETTY_PRINT));
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



        $topic_standards = $controller->getStandardsByTopicId($id);
        $topic_profiles = $controller->getProfileByTopicId($id);

        //$result['documents'] =
        $documents = array();
        foreach ($topic_standards as $standard) {
            $standard = $standard->toArray();
            var_dump($standard);
            $standard['document'] = 'standard';
            array_push($documents, $standard);
        }
        foreach ($topic_profiles as $profile) {
            $profile = $profile->toArray();
            $profile['document'] = 'profile';
            array_push($documents, $profile);
        }
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
        $mapper = new TopicDbMapper();
        $assoc = $this->body;
        $topic = new Topic(
            $this->id, null,
            $assoc['title'],
            $assoc['description'],
            $assoc['number'],
            $assoc['is_in_catalog'],
            $assoc['sequence'],
            $assoc['parent']);
        $response = $mapper->update($topic);

        if ($response instanceof DBError) {
            return new ErrorResponse($response);
        }
        $result =  $mapper->getTopicById($response)->toArray();
        return new Response(json_encode($result, JSON_PRETTY_PRINT));
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
