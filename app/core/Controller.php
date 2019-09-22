<?php

namespace SimpleRest\core;

class Controller
{
    function __construct() {
        $this->config = include CONFIG_PATH . 'config.php';
    }

    function view(string $view_path, array $vars_to_be_passed  = null, $layout = 'app_layout.php'){
        $view = new View($view_path, $vars_to_be_passed, $layout); 
    }

}