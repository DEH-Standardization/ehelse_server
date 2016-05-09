<?php
require_once __DIR__ . '/../../responses/Response.php';
require_once __DIR__ . '/../../responses/ResponseController.php';
require_once __DIR__ . '/../../errors/InvalidPathError.php';
require_once __DIR__ . '/../../errors/ErrorController.php';
require_once __DIR__ . '/../../dbmappers/TopicDBMapper.php';
require_once __DIR__ . '/../../models/Document.php';
require_once __DIR__ . '/../../errors/CantDeleteError.php';

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
        $this->db_mapper = 'TopicDBMapper';
        $this->list_name = 'topics';
        $this->model = 'Topic';

        if (count($this->path) != 0) {
            if (count($this->path) == 1 && is_numeric($path[0])) {
                $this->id = $path[0];
            } else {
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
        $topics = $mapper->getTopicsAsThree();  // Retrieves tree representation of topics
        $topics_array = [];
        foreach ($topics as $topic) {
            $array = $topic->toArray();
            array_push($topics_array, $array);
        }

        $json = json_encode(array("topics" => $topics_array), JSON_PRETTY_PRINT);

        return new Response($json);
    }

    /**
     * Function retrieving a topic with it's documents.
     * @return Response
     */
    protected function get()
    {
        $result = $this->getChildren($this->id);
        if ($result) {

            return new Response(json_encode($result, JSON_PRETTY_PRINT));
        } else {
            return new ErrorResponse(new NotFoundError());
        }
    }

    /**
     * Returns topic array with children
     * @param $id
     * @return mixed
     */
    private function getChildren($id)
    {
        $controller = new TopicDbMapper();
        $topic = $controller->getById($id);
        if ($topic) {
            $result = $topic->toArray();
            $topic_children = $controller->getSubtopicsByTopicId($id);

            $children = array();
            if ($topic_children) {
                foreach ($topic_children as $child) {

                    if (count($topic_children) > 0) {
                        array_push($children, $this->getChildren($child->getId()));
                    } else {
                        array_push($children, $child->toArray());
                    }
                }
            }
            $result['children'] = $children;

            $result['documents'] = array_merge($result['documents'], $this->getDocuments($id));
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

        // Sets profiles, links, fields and target groups for each document
        foreach ($documents as $document) {
            $document->setTargetGroups(DocumentController::getTargetGroups($document));
            $document->setLinks(DocumentController::getLinks($document));
            $document->setFields(DocumentController::getFields($document));

            $document_array = $document->toArray();
            array_push($documents_array, $document_array);
        }

        // Sort document list on sequence
        usort($documents_array, function ($a, $b)
        {
            return $a['sequence'] - $b['sequence'];
        });

        return $documents_array;
    }

    /**
     * Function deleting a topic.
     * @return Response
     */
    protected function delete()
    {
        $mapper = new TopicDbMapper();

        // Check if topic has subtopics or documents, deleting is not allowed if topic is not empty
        if (!($mapper->hasSubtopic($this->id) || $mapper->hasDocuments($this->id)))
            return new Response($mapper->deleteById($this->id), Response::STATUS_CODE_NO_CONTENT);
        else
            return new ErrorResponse(new CantDeleteError());
    }

}
