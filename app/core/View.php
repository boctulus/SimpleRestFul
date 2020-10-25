<?php

namespace simplerest\core;

use simplerest\traits\ExceptionHandler;
use simplerest\libs\Factory;

include HELPERS_PATH . 'view.php';

class View
{
    use ExceptionHandler;

    function __construct(string $view_path, array $vars_to_be_passed  = null, $layout = 'app_layout.php')
    {
		$this->config = Factory::config();

		if ($this->config['error_handling']) {
            set_exception_handler([$this, 'exception_handler']);
        }

        if (!empty($vars_to_be_passed))
            extract($vars_to_be_passed);                

        ob_start();
        include VIEWS_PATH . $view_path;
        $content = ob_get_contents();
        ob_end_clean();

        include VIEWS_PATH . "layouts/{$layout}"; 
    }
}