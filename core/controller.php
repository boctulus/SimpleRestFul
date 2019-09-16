<?php

namespace Core;

class Controller
{
    function __construct() {
        $this->config = include ROOT_PATH . 'config/config.php';
    }

    function view(string $view_path, array $vars_to_be_passed  = null, $layout = 'app_layout.php'){
		ob_start();
		require_once("views/{$view_path}");
		$content = ob_get_contents();
		ob_end_clean();

        extract($vars_to_be_passed);

        include "views/layouts/{$layout}";
    }

}