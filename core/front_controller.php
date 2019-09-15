<?php

    require_once 'controller.php';
    require_once 'helpers/html.php';
    include 'helpers/debug.php';
    require_once 'request.php';
    require_once 'response.php';

    
    class FrontController
    {
        const DEFAULT_ACTION = "index";

        static function resolve()
        {
            $config = include 'config/config.php';

            $req = Request::getInstance();

            $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
            if (strpos($path, $config['BASE_URL']) === 1) {
                $path = substr($path, strlen($config['BASE_URL'])+1) ;
            }

            $_params = explode('/', $path);
            //debug ($_params);

            if (empty($_params[0]))  
                array_shift($_params);

            // parche
            if ($_params[0]=='api'){
                @list($controller) = array_slice($_params,1,1);
                $params = array_slice($_params,2);
                include CORE_PATH.'api_router.php';
                $req->setParams($params); ///
                ApiRouter::resolve($controller, strtolower($_SERVER['REQUEST_METHOD']), $params);
            }else{
                @list($controller, $action) = array_slice($_params,0,2);
                $params = array_slice($_params,2);
                $req->setParams($params); ///
            }

            //echo "CONTROLLER: $controller";

            $default_controller_name = substr($config['DEFAULT_CONTROLLER'],0, strlen($config['DEFAULT_CONTROLLER'])-10);
            $class_file = !empty($controller) ? $controller : $default_controller_name;
            $method = !empty($action) ? $action : self::DEFAULT_ACTION;

            $class_name = ucfirst($class_file).'Controller';
       
            if (!method_exists($class_name, $method)) 
                throw new Exception ("method '$method' does not exist in $class_name");
            
           
            // $arr = $req->toArray();  
            // $data = call_user_func([new $class_name(), $method],$req);
            
            $data = call_user_func_array([new $class_name(), $method], $params);  
            Response::getInstance()->send($data);
            exit;
        }
    }

    