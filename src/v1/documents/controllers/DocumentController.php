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
require_once __DIR__ . '/../../dbmappers/DocumentVersionTargetGroupDBMapper.php';
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

        $links_db_mapper = new LinkDBMapper();
        $document_models = $document_mapper->getAll();
        $topics_array = [];

        /*
        foreach($document_models as $document){
            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
            $document = new Document(null,null,null,null,null,null,null,null,null,null,null);
            // !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

            //$document->setLinks();
            $target_group =

            $document->setTargetGroups($this->getTargetGroups());

            $document_array = $document->toArray();
            $document_array['status'] = $status_mapper->getById($document->getStatusId())->getName();
            $document_array['documentType'] = $document_type_mapper->getById($document->getDocumentTypeId())->getName();

            array_push($topics_array, $document);

        }
        $json = json_encode(array( "documents" => $topics_array), JSON_PRETTY_PRINT);

        return new Response($json);
        */

        $target_group_mapper = new DocumentVersionTargetGroupDBMapper();
        //echo 'tt';
        print_r($target_group_mapper->getAllTargetGroupIdsByDocumentVersionId(2));
        return new Response("dd");
    }

    private function getTargetGroups($document)
    {
        $document_target_group_mapper = new DocumentVersionTargetGroupDBMapper();
        $action_mapper = new ActionDBMapper();
        $mandatory_mapper = new MandatoryDBMapper();

        $ddd = $document_target_group_mapper->getTargetGroupsByDocumentIdAndDocumentTimestamp(
            $document->getId(), $document->getTimestamp());

        $target_group_array = [];
        foreach ($ddd as $tg) {
            $tg->setAction($action_mapper->getById($tg->getTargetGroupId())->getName());
            $tg->setMandatory($mandatory_mapper->getById($tg->getMandatoryId())->getName());
            array_push($target_group_array, $tg->toArray());
        }

        return $target_group_array;
    }

    private function getLinks()
    {
        $link_mapper = new LinkDBMapper();
        $link_array = array();

        $link_categories = $link_mapper->getLinkCategoriesByDocumentId($this->id);
        foreach ($link_categories as $category) {
            $links = $link_mapper->getLinksByDocumentIdAndLinkCategoryId($category['id'],$this->id);
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
        $document->setLinks($this->getLinks());

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