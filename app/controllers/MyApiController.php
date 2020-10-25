<?php

namespace simplerest\controllers;

global $api_version;

if (!file_exists(CORE_PATH . 'api'. DIRECTORY_SEPARATOR . $api_version . DIRECTORY_SEPARATOR . 'ApiController.php')){  
    \simplerest\core\Response::getInstance()->sendError("API version $api_version is not supported", 400);
}

class_alias("\simplerest\core\api\\$api_version\ApiController", 'simplerest\controllers\ApiController');
class_alias("\simplerest\core\api\\$api_version\AuthController",'simplerest\controllers\AuthController');

class MyApiController extends ApiController
{
    static protected $folder_field;
        
    function __construct()
    {
        parent::__construct();
    }

}