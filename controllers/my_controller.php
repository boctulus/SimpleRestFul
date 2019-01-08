<?php

require_once 'core/controller.php';

class My_Controller extends Controller {
    protected $_title;

    public function __construct(){
        parent::__construct();
        $this->_title = str_replace('Controller','',get_class($this));
    }

    function view(string $view_path, array $vars_to_be_passed = null, $layout = 'app_layout.php'){
        if(!isset($vars_to_be_passed['title'])){
            $vars_to_be_passed['title'] = $this->_title;
        }

        parent::view($view_path, $vars_to_be_passed, $layout);
   }

}