<?php

    require_once 'controller.php';
    
    class FrontController
    {
        const DEFAULT_ACTION     = "index";

        static function resolve()
        {
            $config = include 'config/config.php';

            $default_controller_name = str_replace('Controller','',$config['DEFAULT_CONTROLLER']);
            $params = $_REQUEST;
            $class_file = $_GET['c'] ?? $default_controller_name;
            $method = $_GET['a'] ?? self::DEFAULT_ACTION;

            include "controllers/$class_file.php";
            $class_name = ucfirst($class_file).'Controller';
       
            if (!method_exists($class_name, $method)) 
                throw new Exception ("method '$method' does not exist in $class_name");
            
            call_user_func_array(array(new $class_name(), $method), $params);
            
        }
    }

    