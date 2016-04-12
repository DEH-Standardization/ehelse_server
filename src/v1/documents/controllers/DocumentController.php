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
    public function __construct($path, $method, $body)
    {
        $this->init($path, $method, $body);
        $this->db_mapper = 'DocumentDBMapper';
        $this->list_name = 'document';
        $this->model = 'Document';
    }

    protected function getAll()
    {
        $document_mapper = new DocumentDBMapper();

        $documents = $document_mapper->getAll();
        $document_array = array();
        foreach ($documents as $document) {
            $document->setLinks($this->getLinks($document));
            $document->setFields($this->getFields($document));
            $document->setTargetGroups($this->getTargetGroups($document));
            array_push($document_array, $document->toArray());
        }

        $json = json_encode(array( "documents" => $document_array), JSON_PRETTY_PRINT);
        return new Response($json);
    }

    public static function getTargetGroups($document)
    {
        $document_target_group_mapper = new DocumentTargetGroupDBMapper();

        $target_groups = $document_target_group_mapper->getTargetGroupsByDocumentIdAndDocumentTimestamp(
            $document->getId(), $document->getTimestamp());

        $target_group_array = [];
        foreach ($target_groups as $target_group) {
            array_push($target_group_array, $target_group->toArray());
        }

        return $target_group_array;

    }

    public static function getLinks($document)
    {
        $link_mapper = new LinkDBMapper();

        $links = $link_mapper->getLinksByDocumentId($document->getId());
        $link_array = [];
        foreach ($links as $l) {
            array_push($link_array, $l->toArray());
        }

        return $link_array;
    }

    public static function getFields($document)
    {
        $field_mapper = new DocumentFieldDBMapper();
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


    protected function create()
    {
        $response = null;
        $document_mapper = new DocumentDBMapper();
        $document = Document::fromJSON($this->body);
        $result = $document_mapper->add($document);
        if (!$result instanceof DBError) {
            $response = $document_mapper->getById($result)->toJSON();
        } else {
            $response = $result;
        }
        return new Response($response);
    }


    protected function get()
    {
        $document_mapper = new DocumentDBMapper();
        $document = $document_mapper->getById($this->id);

        $document->setLinks($this->getLinks($document));
        $document->setFields($this->getFields($document));
        $document->setTargetGroups($this->getTargetGroups($document));

        $document_array = $document->toArray();

        $json = json_encode(array( "documents" => $document_array), JSON_PRETTY_PRINT);
        return new Response($json);
    }

    protected function update()
    {
        $response = null;
        $document_mapper = new DocumentDBMapper();
        $document = Document::fromJSON($this->body);
        $document->setId($this->id);    // set id from this model
        $result = $document_mapper->update($document);
        if (!$result instanceof DBError) {
            $response = $document_mapper->getById($result)->toJSON();
        } else {
            $response = $result;
        }
        return new Response($response);
    }

    protected function delete()
    {
        // TODO: Implement delete() method.
    }
}