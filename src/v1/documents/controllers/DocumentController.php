<?php

require_once  __DIR__ . '/../../responses/ResponseController.php';
require_once __DIR__ . '/../../models/Document.php';
require_once __DIR__ . '/../../errors/MalformedJSONFormatError.php';
require_once __DIR__ . '/../../responses/ErrorResponse.php';
require_once __DIR__ . '/../../dbmappers/DocumentDBMapper.php';
require_once __DIR__ . '/../../dbmappers/StatusDBMapper.php';
require_once __DIR__ . '/../../dbmappers/DocumentTypeDBMapper.php';
require_once __DIR__ . '/../../dbmappers/LinkDBMapper.php';
require_once __DIR__ . '/../../dbmappers/DocumentTypeDBMapper.php';
require_once __DIR__ . '/../../dbmappers/DocumentTargetGroupDBMapper.php';
require_once __DIR__ . '/../../dbmappers/ActionDBMapper.php';
require_once __DIR__ . '/../../dbmappers/MandatoryDBMapper.php';
require_once __DIR__ . '/../../responses/Response.php';

class DocumentController extends ResponseController
{
    private $document_type;

    public function __construct($path, $method, $body)
    {
        $this->method = $method;

        $this->body = $body;
        $this->path = $path;

        if(count($path) >= 1 && $path[0] == 'fields'){
            $this->controller = new DocumentFieldController(array_shift($path),$this->method,$this->body);
        }
        elseif(count($path) >= 1){
            if(is_numeric($path[0])){
                $this->id = $path[0];
                $path = trimPath($path, 1);
                //TODO document field controller
            }else{
                $this->controller = new ErrorController(new InvalidPathError());
            }
        }
    }

    protected function getAll()
    {
        $document_mapper = new DocumentDBMapper();
        $status_mapper = new StatusDBMapper();
        $document_type_mapper = new DocumentTypeDBMapper();

        $documents = $document_mapper->getAll();

        $documents_array = array();
        foreach ($documents as $document) {
            $document->setTargetGroups($this->getTargetGroups($document));
            $document->setLinks($this->getLinks($document));

            //echo "doc___________________: ";
            //print_r($document);

            $document_array = $document->toArray();
            $document_array['status'] = $status_mapper->getById($document->getStatusId())->getName();
            $document_array['documentTypeId'] = $document_type_mapper->getById($document->getDocumentTypeId())->getName();

            array_push($documents_array, $document_array);

        }
        $json = json_encode(array( "documents" => $documents_array), JSON_PRETTY_PRINT);
        return new Response($json);





        $json = json_encode(array( "documents" => $documents), JSON_PRETTY_PRINT);

        return new Response($json);


    }

    private function getTargetGroups($document)
    {
        $document_target_group_mapper = new DocumentTargetGroupDBMapper();
        $action_mapper = new ActionDBMapper();
        $mandatory_mapper = new MandatoryDBMapper();

        $target_groups = $document_target_group_mapper->getTargetGroupsByDocumentIdAndDocumentTimestamp(
            $document->getId(), $document->getTimestamp());

        $target_group_array = [];

        foreach ($target_groups as $target_group) {
            // Action
            $action = $action_mapper->getById($target_group->getActionId());
            $target_group->setAction($action->getName());
            // Mandatory
            $mandatory = $mandatory_mapper->getById($target_group->getMandatoryId());
            $target_group->setMandatory($mandatory->getName());
            array_push($target_group_array, $target_group->toArray());
        }

        return $target_group_array;
    }

    private function getLinks($document)
    {
        $link_mapper = new LinkDBMapper();
        $link_array = array();

        $link_categories = $link_mapper->getLinkCategoriesByDocumentId($document->getId());
        foreach ($link_categories as $category) {
            $links = $link_mapper->getLinksByDocumentIdAndLinkCategoryId($category['id'],$document->getId());
            $json_links= array();
            foreach ($links as $l) {
                array_push($json_links, $l->toArray());
            }
            $category_array = array(
                'linkCategory' => $category,
                'links' => $json_links
            );
            array_push($link_array, $category_array);
        }
        return $link_array;
    }

    protected function create()
    {
        // TODO: Implement create() method.
        $missing_fields = ResponseController::validateJSONFormat($this->body,Document::REQUIRED_POST_FIELDS);
        if( $missing_fields ){
            $response = new ErrorResponse(new MalformedJSONFormatError($missing_fields));
        }

        return $response;
    }

    protected function get()
    {
        $document_mapper = new DocumentDBMapper();
        $status_mapper = new StatusDBMapper();
        $document_type_mapper = new DocumentTypeDBMapper();

        $document = $document_mapper->getById($this->id);

        $document->setTargetGroups($this->getTargetGroups($document));
        $document->setLinks($this->getLinks($document));

        $document_array = $document->toArray();
        $document_array['status'] = $status_mapper->getById($document->getStatusId())->getName();
        $document_array['documentTypeId'] = $document_type_mapper->getById($document->getDocumentTypeId())->getName();


        $json = json_encode(array( "documents" => $document_array), JSON_PRETTY_PRINT);

        return new Response($json);
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