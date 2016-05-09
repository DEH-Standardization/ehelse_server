<?php

require_once 'iController.php';
require_once 'documents/controllers/DocumentController.php';
require_once 'topics/controllers/TopicController.php';
require_once 'users/controllers/UserController.php';
require_once 'target-groups/controllers/TargetGroupController.php';
require_once 'link-category/controllers/LinkCategoryController.php';
require_once 'actions/ActionController.php';
require_once 'status/StatusController.php';
require_once 'mandatory/MandatoryController.php';
require_once __DIR__ . '/models/User.php';
require_once 'documents/controllers/DocumentTypeController.php';
require_once 'documents/controllers/DocumentFieldController.php';

class APIV1Controller implements iController
{
    private $controller;

    /**
     * APIV1Controller constructor.
     * @param $path
     * @param $method
     * @param $body
     */
    public function __construct($path, $method, $body)
    {
        $part = $path[0];
        $path = trimPath($path, 1);

        // Choose path
        switch ($part) {
            case 'documents':
                $this->controller = new DocumentController($path, $method, $body);
                break;
            case 'topics':
                $this->controller = new TopicController($path, $method, $body);
                break;
            case 'users':
                $this->controller = new UserController($path, $method, $body);
                break;
            case 'target-groups':
                $this->controller = new TargetGroupController($path, $method, $body);
                break;
            case 'link-categories':
                $this->controller = new LinkCategoryController($path, $method, $body);
                break;
            case 'actions':
                $this->controller = new ActionController($path, $method, $body);
                break;
            case 'mandatory':
                $this->controller = new MandatoryController($path, $method, $body);
                break;
            case 'status':
                $this->controller = new StatusController($path, $method, $body);
                break;
            case 'document-types':
                $this->controller = new DocumentTypeController($path, $method, $body);
                break;
            case 'document-fields':
                $this->controller = new DocumentFieldController($path, $method, $body);
                break;
            default:
                $this->controller = new ErrorController(new InvalidPathError());
                break;
        }
    }

    /**
     * Returns response from controller
     * @return ErrorResponse
     */
    public function getResponse()
    {
        return $this->controller->getResponse();
    }
}