<?php

namespace simplerest\controllers;

use simplerest\core\ResourceController;
use simplerest\core\Request;
use simplerest\libs\Factory;
use simplerest\models\RolesModel;

class DumbAuthController extends ResourceController
{
    function __construct()
    {
        parent::__construct();
    }

    function super_cool_action($a)
    {
        if (!$this->hasAnyRole(['cajero', 'gerente']))
            Factory::response()->sendError('Unauthorized', 401);

        // acción cualquiera:
        return ++$a;
    }

       
}