<?php

namespace SimpleRest\controllers;

use SimpleRest\core\Controller;

class MyController extends Controller {
    protected $_title;

    public function __construct(){
        parent::__construct();
        $_ctrl = explode('\\',get_class($this));
        $ctrl  = $_ctrl[count($_ctrl)-1];
        $this->_title = substr($ctrl,0,strlen($ctrl)-10);
    }

    function view(string $view_path, array $vars_to_be_passed = null, $layout = 'app_layout.php'){
        if(!isset($vars_to_be_passed['title'])){
            $vars_to_be_passed['title'] = $this->_title;
        }

        parent::view($view_path, $vars_to_be_passed, $layout);
   }

}