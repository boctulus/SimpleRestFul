<?php

namespace Core;

header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS');
header('access-control-allow-credentials: true');
header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
header('access-control-allow-Origin: *');
header('content-type: application/json; charset=UTF-8');

require_once 'config/constants.php';
require_once ROOT_PATH . 'core/auth/check.php';
require_once LIBS_PATH . 'database.php';
include_once HELPERS_PATH . 'debug.php';
include_once HELPERS_PATH . 'arrays.php';
include_once HELPERS_PATH . 'messages.php';


abstract class ApiController
{
    protected $config;
    protected $_model;

    function __construct() 
    {
        $this->config = include ROOT_PATH . 'config/config.php';

        if ($this->config['debug_mode'] == false)
            set_exception_handler([$this, 'exception_handler']);

        if ($this->config['enabled_auth'])
            check_auth();	

        if (preg_match('/([A-Z][a-z0-9_]+)Controller/', get_called_class(), $matchs)){
            $this->_model = $matchs[1] . 'Model';
        }    
    }

    function exception_handler($e) {
        response()->sendError($e->getMessage());
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
    
        $_get   = request()->getQuery();
    
        $fields = shift($_get,'fields');
        ///
        ///    debug($fields);
        ///
        $fields = $fields != NULL ? explode(',',$fields) : NULL;

        if ($id != null)
        {
            // one instance by id
            $instance->id = $id; 
            if ($instance->fetch($fields) === false)
                response()->sendCode(404);
            else
                response()->send($instance);
        }else{    
            // "list
            $limit  = (int) shift($_get,'limit');
            $offset = (int) shift($_get,'offset',0);
            $order  = shift($_get,'order');

            try {
                if (!empty($_get)){
                    $rows = $instance->filter($fields, $_get, $order, $limit, $offset);
                    response()->code(empty($rows) ? 404 : 200)->send($rows); 
                }else {
                    $rows = $instance->fetchAll($fields, $order, $limit, $offset);
                    response()->code(empty($rows) ? 404 : 200)->send($rows); 
                }	
            } catch (\Exception $e) {
                response()->sendError('Error in fetch: '.$e->getMessage());
            }	
        }
    } // end method

    function post(){
        $data = request()->getBody();

        if (empty($data))
            response()->sendError('Invalid JSON',400);
        
        $model    = '\\Models\\'.$this->_model;
        $instance = new $model();

        $missing = $instance::diffWithSchema($data, ['id']);
        if (!empty($missing))
            response()->sendError('Lack some properties in your request: '.implode(',',$missing));
    
        $conn = \Libs\Database::getConnection($this->config['database']);
        $instance->setConn($conn);

        if ($instance->create($data)!==false){
            response()->send(['id' => $instance->id], 201);
        }	
        else
            response()->sendError("Error: creation of resource fails!");
    } // end method
    
        
    function put($id = null){
        if ($id == null)
            response()->code(400)->sendError("Lacks id in request");

        $data = request()->getBody();

        if (empty($data))
            response()->sendError('Invalid JSON',400);
        
        $model    = '\\Models\\'.$this->_model;
        $instance = new $model();
        $instance->id = $id;

        $missing = $instance::diffWithSchema($data, ['id']);
        if (!empty($missing))
            response()->sendError('Lack some properties in your request: '.implode(',',$missing));
        
        $conn = \Libs\Database::getConnection($this->config['database']);
        $instance->setConn($conn);

        $instance->id = $id;
        if (!$instance->exists()){
            response()->code(404)->sendError("Register for id=$id does not exists");
        }
        
        try {

            if($instance->update($data)!==false)
                response()->sendJson("OK");
            else
                response()->sendError("Error in UPDATE");

        } catch (\Exception $e) {
            response()->sendError("Error during update for id=$id with message: {$e->getMessage()}");
        }
    } // end method
    
        
    function delete($id = NULL){
        if($id == NULL)
            response()->sendError("Lacks id in request",400);

        $conn = \Libs\Database::getConnection($this->config['database']);
        
        $model    = '\\Models\\'.$this->_model;
        $instance = new $model($conn);
        $instance->id = $id;

        if($instance->delete()){
            response()->sendJson("OK");
        }	
        else
            response()->sendError("Record not found",404);
    } // end method

    
    function patch($id = NULL){ 
        if ($id == null)
            response()->sendError("Lacks id in request",400);

        $data = request()->getBody();

        if (empty($data))
            response()->sendError('Invalid JSON',400);
        
        $conn = \Libs\Database::getConnection($this->config['database']);

        $model    = '\\Models\\'.$this->_model;
        $instance = new $model($conn);
        $instance->id = $id;

        try {
            if($instance->update($data)!==false)
                response()->sendJson("OK");
            else
                response()->sendError("Error in PATCH",404);	
        } catch (\Exception $e) {
            response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
        }
    } // end method
                
    
}    