<?php

require_once __DIR__ . '/../../responses/ResponseController.php';
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
    /**
     * DocumentController constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'DocumentDBMapper';
        $this->list_name = 'document';
        $this->model = 'Document';
    }

    /**
     * Retrieving all documents
     * @return Response
     */
    protected function getAll()
    {
        $document_mapper = new DocumentDBMapper();

        $documents = $document_mapper->getAll();

        if ($documents === null) {
            return new ErrorResponse(new NotFoundError());
        }

        $document_array = array();
        foreach ($documents as $document) {
            $document->setProfiles($this->getProfiles($document));
            $document->setLinks($this->getLinks($document));
            $document->setFields($this->getFields($document));
            $document->setTargetGroups($this->getTargetGroups($document));
            array_push($document_array, $document->toArray());
        }

        $json = json_encode(array("documents" => $document_array), JSON_PRETTY_PRINT);
        return new Response($json);
    }

    /**
     * Retrieving all target groups under documents
     * @param $document
     * @return array
     */
    public static function getTargetGroups($document)   // TODO: Might be better to move this to TargetGroup
    {
        $document_target_group_mapper = new DocumentTargetGroupDBMapper();

        $target_groups = $document_target_group_mapper->getTargetGroupsByDocumentIdAndDocumentTimestamp(
            $document->getId(),
            $document->getTimestamp()
        );

        $target_group_array = [];
        foreach ($target_groups as $target_group) {
            array_push($target_group_array, $target_group->toArray());
        }

        return $target_group_array;

    }

    /**
     * Retrieving all profiles under documents
     * @param $document
     * @return array
     */
    public static function getProfiles($document)
    {
        $document_mapper = new DocumentDBMapper();
        /*  Returns profiles (Document objects)

        $profiles = $document_mapper->getProfiles($document->getId());
        $profiles_array = [];
        foreach ($profiles as $profile) {
            $profile->setProfiles(DocumentController::getProfiles($profile));
            $profile->setLinks(DocumentController::getLinks($profile));
            $profile->setFields(DocumentController::getFields($profile));
            $profile->setTargetGroups(DocumentController::getTargetGroups($profile));
            array_push($profiles_array, $profile->toArray());
        }

        return $profiles_array;
        */

        return $document_mapper->getProfileIds($document->getId());
    }

    /**
     * Retrieving all links under documents
     * @param $document
     * @return array
     */
    public static function getLinks($document)  // TODO: Might be better to move this to Link
    {
        $link_mapper = new LinkDBMapper();

        $links = $link_mapper->getLinksByDocumentId($document->getId(), $document->getTimestamp());
        $link_array = [];
        foreach ($links as $link) {
            array_push($link_array, $link->toArray());
        }

        return $link_array;
    }

    /**
     * Retrieving all fields under documents
     * @param $document
     * @return array
     */
    public static function getFields($document) // TODO: Might be better to move this to Field
    {
        $field_mapper = new DocumentFieldValueDBMapper();
        $fields = $field_mapper->getFieldsByDocumentIdAndDocumentTimestamp(
            $document->getId(),
            $document->getTimestamp()
        );
        $field_array = [];
        foreach ($fields as $field) {
            array_push($field_array, $field->toArray());
        }
        return $field_array;
    }


    /**
     * Creates new document with related links, target groups and links
     * @return Response
     */
    protected function create()
    {
        $response = null;
        $document_mapper = new DocumentDBMapper();
        $document = Document::fromJSON($this->body);

        if ($document->getInternalId() === null) {
            return new ErrorResponse(new InvalidJSONError('Internal id cannot be null.'));
        }
        if (!$document_mapper->isValidInternalId($document->getInternalId())) {
           return new ErrorResponse(new InvalidJSONError('Internal id is not unique.'));
        }
        


        $result = $document_mapper->add($document);
        if (!$result instanceof DBError) {
            return $this->getById($result);
        } else {
            $response = $result->toJSON();
        }




        return new Response($response);
    }


    /**
     * Retrieving documents based on document model
     * @return Response
     */
    protected function get()
    {
        return $this->getById($this->id);
    }

    /**
     * Retrieving documents based on id
     * @param $id
     * @return Response
     */
    private function getById($id)
    {
        $document_mapper = new DocumentDBMapper();
        $document = $document_mapper->getById($id);

        if ($document === null) {
            return new ErrorResponse(new NotFoundError());
        }

        $document->setProfiles($this->getProfiles($document));
        $document->setLinks($this->getLinks($document));
        $document->setFields($this->getFields($document));
        $document->setTargetGroups($this->getTargetGroups($document));

        $json = json_encode($document->toArray(), JSON_PRETTY_PRINT);
        return new Response($json);
    }

    /**
     * Updates document with related links, target groups and links, by adding new document
     * @return Response
     */
    protected function update()
    {
        $response = null;
        $document_mapper = new DocumentDBMapper();
        $document = Document::fromJSON($this->body);
        $document->setId($this->id);
        $result = $document_mapper->update($document);
        if (!$result instanceof DBError) {
            return $this->getById($result);
        } else {
            $response = $result->toJSON();
        }
        return new Response($response);
    }


}