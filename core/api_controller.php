<?php

namespace Core;

require_once 'config/constants.php';
require_once ROOT_PATH . 'core/auth/check.php';
require_once LIBS_PATH . 'database.php';
require_once LIBS_PATH . 'arrays.php';


abstract class ApiController
{
    protected $config;
    protected $_model;

    function __construct() 
    {
        $this->headers();

        $this->config = include ROOT_PATH . 'config/config.php';

        if ($this->config['debug_mode'] == false)
            set_exception_handler([$this, 'exception_handler']);

        if ($this->config['enabled_auth'])
            check_auth();	

        if (preg_match('/([A-Z][a-z0-9_]+)Controller/', get_called_class(), $matchs)){
            $this->_model = $matchs[1] . 'Model';
        }    
    }

    protected function headers() {
        header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS');
        header('access-control-allow-credentials: true');
        header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
        header('access-control-allow-Origin: *');
        header('content-type: application/json; charset=UTF-8');
    }

    function exception_handler($e) {
        \Libs\Factory::response()->sendError($e->getMessage());
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

    function get(int $id = null){
        $conn = \Libs\Database::getConnection($this->config['database']);

        $model    = '\\Models\\'.$this->_model;
        $instance = new $model($conn); 
    
        $_get   = \Libs\Factory::request()->getQuery();
    
        $fields = \Libs\Arrays::shift($_get,'fields');
        $fields = $fields != NULL ? explode(',',$fields) : NULL;
        
        ///
        $exclude = \Libs\Arrays::shift($_get,'exclude');
        $exclude = $exclude != NULL ? explode(',',$exclude) : NULL;

        if ($exclude != null)
            $instance->hide($exclude);

        if ($id != null)
        {
            // one instance by id
            $instance->id = $id; 
            if ($instance->fetch($fields) === false)
                \Libs\Factory::response()->sendCode(404);
            else
                \Libs\Factory::response()->send($instance);
        }else{    
            // "list
            $limit  = (int) \Libs\Arrays::shift($_get,'limit');
            $offset = (int) \Libs\Arrays::shift($_get,'offset',0);
            $order  = \Libs\Arrays::shift($_get,'order');

            try {
                if (!empty($_get)){
                    $rows = $instance->filter($fields, $_get, $order, $limit, $offset);
                    \Libs\Factory::response()->code(empty($rows) ? 404 : 200)->send($rows); 
                }else {
                    $rows = $instance->fetchAll($fields, $order, $limit, $offset);
                    \Libs\Factory::response()->code(empty($rows) ? 404 : 200)->send($rows); 
                }	
            } catch (\Exception $e) {
                \Libs\Factory::response()->sendError('Error in fetch: '.$e->getMessage());
            }	
        }
    } // end method

    function post(){
        $data = \Libs\Factory::request()->getBody();

        if (empty($data))
            \Libs\Factory::response()->sendError('Invalid JSON',400);
        
        $model    = '\\Models\\'.$this->_model;
        $instance = new $model();

        $missing = $instance::diffWithSchema($data, ['id']);
        if (!empty($missing))
            \Libs\Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));
    
        $conn = \Libs\Database::getConnection($this->config['database']);
        $instance->setConn($conn);

        if ($instance->create($data)!==false){
            \Libs\Factory::response()->send(['id' => $instance->id], 201);
        }	
        else
            \Libs\Factory::response()->sendError("Error: creation of resource fails!");
    } // end method
    
        
    function put($id = null){
        if ($id == null)
            \Libs\Factory::response()->code(400)->sendError("Lacks id in request");

        $data = \Libs\Factory::request()->getBody();

        if (empty($data))
            \Libs\Factory::response()->sendError('Invalid JSON',400);
        
        $model    = '\\Models\\'.$this->_model;
        $instance = new $model();
        $instance->id = $id;

        $missing = $instance::diffWithSchema($data, ['id']);
        if (!empty($missing))
            \Libs\Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));
        
        $conn = \Libs\Database::getConnection($this->config['database']);
        $instance->setConn($conn);

        $instance->id = $id;
        if (!$instance->exists()){
            \Libs\Factory::response()->code(404)->sendError("Register for id=$id does not exists");
        }
        
        try {

            if($instance->update($data)!==false)
                \Libs\Factory::response()->sendJson("OK");
            else
                \Libs\Factory::response()->sendError("Error in UPDATE");

        } catch (\Exception $e) {
            \Libs\Factory::response()->sendError("Error during update for id=$id with message: {$e->getMessage()}");
        }
    } // end method
    
        
    function delete($id = NULL){
        if($id == NULL)
            \Libs\Factory::response()->sendError("Lacks id in request",400);

        $conn = \Libs\Database::getConnection($this->config['database']);
        
        $model    = '\\Models\\'.$this->_model;
        $instance = new $model($conn);
        $instance->id = $id;

        if($instance->delete()){
            \Libs\Factory::response()->sendJson("OK");
        }	
        else
            \Libs\Factory::response()->sendError("Record not found",404);
    } // end method

    
    function patch($id = NULL){ 
        if ($id == null)
            \Libs\Factory::response()->sendError("Lacks id in request",400);

        $data = \Libs\Factory::request()->getBody();

        if (empty($data))
            \Libs\Factory::response()->sendError('Invalid JSON',400);
        
        $conn = \Libs\Database::getConnection($this->config['database']);

        $model    = '\\Models\\'.$this->_model;
        $instance = new $model($conn);
        $instance->id = $id;

        try {
            if($instance->update($data)!==false)
                \Libs\Factory::response()->sendJson("OK");
            else
                \Libs\Factory::response()->sendError("Error in PATCH",404);	
        } catch (\Exception $e) {
            \Libs\Factory::response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
        }
    } // end method
                
    
}    