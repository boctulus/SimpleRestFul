<?php

namespace SimpleRest\core;

class View
{
    function __construct(string $view_path, array $vars_to_be_passed  = null, $layout = 'app_layout.php')
    {
        ob_start();
        include "views/{$view_path}";
        $content = ob_get_contents();
        ob_end_clean();
    
        if (!empty($vars_to_be_passed))
            extract($vars_to_be_passed);

        include VIEWS_PATH . "layouts/{$layout}";
    }
}