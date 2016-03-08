<?php
require_once __DIR__ . "/../../iResponse.php";

class Response implements iResponse
{
    protected $content_type, $body, $response_code;

    const CONTENT_TYPE_JSON = 'Content-Type: application/json';

    const REQUEST_METHOD_GET = 'GET';
    const REQUEST_METHOD_POST = 'POST';
    const REQUEST_METHOD_PUT = 'PUT';
    const REQUEST_METHOD_DELETE = 'DELETE';
    const REQUEST_METHOD_OPTIONS = 'OPTIONS';

    const STATUS_CODE_BAD_REQUEST = 400 ;
    const STATUS_CODE_UNAUTHORIZED = 401 ;
    const STATUS_CODE_FORBIDDEN = 403 ;
    const STATUS_CODE_NOT_FOUND = 404 ;
    const STATUS_CODE_METHOD_NOT_ALLOWED = 405 ;
    const STATUS_CODE_OK = 200;
    const STATUS_CODE_CREATED= 201;
    const STATUS_CODE_ACCEPTED= 202;
    const STATUS_CODE_NO_CONTENT= 204;

    public function __construct($body,
                                $response_code = Response::STATUS_CODE_OK,
                                $content_type = Response::CONTENT_TYPE_JSON)
    {
        $this->body = $body;
        $this->response_code = $response_code;
        $this->content_type = $content_type;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function getContentType()
    {
        return $this->content_type;
    }

    public function getResponseCode()
    {
        return $this->response_code;
    }

}
