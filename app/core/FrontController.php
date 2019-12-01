<?php

namespace simplerest\core;

use simplerest\libs\Url;
use simplerest\libs\Arrays;

class FrontController
{
    const DEFAULT_ACTION = "index";

    static function resolve()
    {
        global $argv;
        global $api_version;

        $config = include '../config/config.php';

        if (php_sapi_name() == 'cli'){
            $_params = $argv;

            @list($controller, $action) = array_slice($_params,1,3);
            $params = array_slice($_params,3);
            $namespace = 'simplerest\\controllers\\';

            if (empty($controller))
                throw new \Exception("Lacks controller specification");

            //if (empty($action))
            //    throw new \Exception("Lacks action specification");    

            $req = Request::getInstance();  
            $req->setParams($params);
            
            $class_file = $controller;
            $method = !empty($action) ? $action : self::DEFAULT_ACTION;
    
            $class_name = ucfirst($class_file);
            $class_name = "${namespace}${class_name}Controller";

        }else{
            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            $path = preg_replace('/(.*)\/index.php/', '/', $path);

            if ($config['BASE_URL'] != '/' && strpos($path, $config['BASE_URL']) === 0) {
                $path = substr($path, strlen($config['BASE_URL']));
            }   

            if ($path === false || ! Url::url_check($_SERVER['REQUEST_URI']) )
                Response::getInstance()->sendError('Malformed url', 400); 

            $_params = explode('/', $path);

            if (empty($_params[0]))  
                array_shift($_params);

            $req = Request::getInstance();            
        
            if ($_params[0]=='api'){
                if (!isset($_params[1]))
                    Response::getInstance()->sendError('API version is missing');

                if (!preg_match('/^v[0-9.]+$/', $_params[1], $matches) )
                    Response::getInstance()->sendError("Incorrect format for API version");

                $api_version = $_params[1]; 
                
                @list($controller) = array_slice($_params,2,1);
                $params = array_slice($_params,3,2);
                $req->setParams($params);    
                
                if ($controller == 'trashCan')
                   $namespace = 'simplerest\\core\\api\\'. $api_version . '\\';
                else
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
        }
       
        /*
            Considerar usar Accept-Language en los headers en su lugar.

            Accept-Language is the opening bid, offering a set of options; Content-Language is the resolution.
        */

        // i18n
        $lang = $req->shift('lang');
        
        if ($lang != NULL)
            setlocale(LC_ALL, "$lang.UTF-8");


        if (!class_exists($class_name))
            Response::getInstance()->sendError("Internal error - controller class $class_name not loaded", 500);  

        if (!method_exists($class_name, $method))
            Response::getInstance()->sendError("Internal error - method $method does not exist in $class_name", 500); 
                
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

