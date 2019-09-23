<?php

namespace simplerest\core;

class ApiRouter
{
    static function resolve($controller, $method, $params)
    {
        $config = include CONFIG_PATH . 'config.php';

        require API_PATH . $controller . '.php';        
        $class_name = ucfirst($controller).'Controller';
        $obj = new $class_name();

        call_user_func_array(array($obj, $method), $params);
        
    }

}