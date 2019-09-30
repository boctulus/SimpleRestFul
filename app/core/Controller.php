<?php

namespace simplerest\core;

use simplerest\libs\Database;

abstract class Controller
{
    protected $allowed  = [];
    protected $callable = [];

    function __construct() {
        $this->config = include CONFIG_PATH . 'config.php';
    }

    function getCallable(){
        return $this->callable;
    }

    function view(string $view_path, array $vars_to_be_passed  = null, $layout = 'app_layout.php'){
        $view = new View($view_path, $vars_to_be_passed, $layout); 
    }

    protected function getConnection() {
        return Database::getConnection($this->config['database']);
    }

}