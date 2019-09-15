<?php

namespace Core;

class ApiRouter
{
    static function resolve($controller, $method, $params)
    {
        $config = include 'config/config.php';

        include "api/$controller".'.php';
        $class_name = ucfirst($controller).'Controller';
        $obj = new $class_name();

        call_user_func_array(array($obj, $method), $params);
        
    }

}