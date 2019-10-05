<?php

namespace simplerest\core;

class FrontController
{
    const DEFAULT_ACTION = "index";

    static function resolve()
    {
        $config = include '../config/config.php';

        $req = Request::getInstance();

        $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (strpos($path, $config['BASE_URL']) === 1) {
            $path = substr($path, strlen($config['BASE_URL'])+1) ;
        }

        $_params = explode('/', $path);

        if (empty($_params[0]))  
            array_shift($_params);

        // patch
        if ($_params[0]=='api'){
            @list($controller) = array_slice($_params,1,1);
            $params = array_slice($_params,2);
            $req->setParams($params);     
            $namespace = 'simplerest\\api\\';
            
            $class_name = $namespace . ucfirst($controller); //
            $method = strtolower($_SERVER['REQUEST_METHOD']);
        }else{
            @list($controller, $action) = array_slice($_params,0,2);
            $params = array_slice($_params,2);
            $req->setParams($params);
            $namespace = 'simplerest\\controllers\\';

            $default_controller_name = substr($config['DEFAULT_CONTROLLER'],0, strlen($config['DEFAULT_CONTROLLER'])-10);
            $class_file = !empty($controller) ? $controller : $default_controller_name;
            $method = !empty($action) ? $action : self::DEFAULT_ACTION;
    
            $class_name = ucfirst($class_file);
            $class_name = "${namespace}${class_name}Controller";
        }
       
        if (!class_exists($class_name))
            throw new \Exception ("Controller class '$class_name' not loaded");  

        if (!method_exists($class_name, $method))
            throw new \Exception ("Method '$method' does not exist in $class_name ***"); 
                   
        $controller_obj = new $class_name();

        // Only for API Rest
        if ($config['enabled_auth'] && $_params[0]=='api'){
            if (!in_array($method, $controller_obj->getCallable())){
                Response::getInstance()->send("Not authorized for $controller:$method", 403);
            }
        }
            
        $data = call_user_func_array([$controller_obj, $method], $params);
        Response::getInstance()->send($data);
        exit;
    }
}

