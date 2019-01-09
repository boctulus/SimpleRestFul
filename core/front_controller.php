<?php

    require_once 'controller.php';
    require_once 'helpers/html.php';
    include 'helpers/debug.php';

    
    class FrontController
    {
        const DEFAULT_ACTION     = "index";

        static function resolve()
        {
            $config = include 'config/config.php';

            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

            if (strpos($path, $config['BASE_URL']) === 1) {
                $path = substr($path, strlen($config['BASE_URL'])+1) ;
            }

            $params = explode('/', $path);

            @list($controller, $action) = array_slice($params,0,2);
            $params = array_slice($params,2);

            //debug($path);
            //debug([$controller, $action, $params]);
            //exit;
            
            $default_controller_name = str_replace('Controller','',$config['DEFAULT_CONTROLLER']);
            $class_file = !empty($controller) ? $controller : $default_controller_name;
            $method = !empty($action) ? $action : self::DEFAULT_ACTION;

            //include "controllers/$class_file.php"; // debe ser Autoload !!!!!!!!!!!!!!!


            $class_name = ucfirst($class_file).'Controller';
       
            if (!method_exists($class_name, $method)) 
                throw new Exception ("method '$method' does not exist in $class_name");
            
            call_user_func_array(array(new $class_name(), $method), $params);
            
        }
    }

    