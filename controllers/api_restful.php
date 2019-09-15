<?php

abstract class ApiRestfulController
{
    function __construct() {
        set_exception_handler([$this, 'exception_handler']);

        $this->config = include ROOT_PATH . 'config/config.php';

        if ($this->config['enabled_auth'])
            check_auth();	

        if (preg_match('/([A-Z][a-z0-9_]+)Controller/', get_called_class(), $matchs)){
            $this->_model = $matchs[1] . 'Model';
        }    
    }

    function exception_handler($e) {
        response()->sendError($e->getMessage());
    }

    // discard conentent (body)
    function head($id = null) {
        if (method_exists($this,'get')){
            ob_start();
            $this->get($id);
            ob_end_clean();
        }
    }

    function options(){
    }
}    