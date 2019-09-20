<?php

namespace Core;

require CORE_PATH . 'view.php';

class Controller
{
    function __construct() {
        $this->config = include ROOT_PATH . 'config/config.php';
    }

    function view(string $view_path, array $vars_to_be_passed  = null, $layout = 'app_layout.php'){
        $view = new View($view_path, $vars_to_be_passed, $layout); 
    }

}