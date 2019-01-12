<?php

class ApiRestfulController
{
    function __construct() {
        $this->config = include ROOT_PATH . 'config/config.php';

        if ($this->config['enabled_auth'])
            check_auth();	
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