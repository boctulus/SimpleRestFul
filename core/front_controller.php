<?php
    // require_once 'controller.php';

    class FrontController
    {
        static function resolve($class_file, $method, $params)
        {
            include "controllers/$class_file.php";
            $class_name = ucfirst($class_file).'Controller';
       
            if (!method_exists($class_name, $method)) { 
                throw new Exception ("method '$method' does not exist in $class_name");
            } else {
                call_user_func_array(array(new $class_name(), $method), $params);
            }
        }
    }

    