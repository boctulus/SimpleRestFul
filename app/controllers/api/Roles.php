<?php

namespace simplerest\controllers\api;

use simplerest\controllers\MyApiController; 
use simplerest\libs\Factory;

class Roles extends MyApiController
{
    function __construct()
    {
        parent::__construct();
    }

    function create($id = null){
        Factory::response()->sendError('Not implemented', 501, "Roles are read-only");
    }

    function put($id = null){
        Factory::response()->sendError('Not implemented', 501, "Roles are read-only");
    }

    function patch($id = null){
        Factory::response()->sendError('Not implemented', 501, "Roles are read-only");
    }

    function delete($id = null){
        Factory::response()->sendError('Not implemented', 501, "Roles are read-only");
    }

} // end class
