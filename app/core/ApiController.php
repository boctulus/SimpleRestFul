<?php

namespace simplerest\core;

use simplerest\controllers\AuthController;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Database;

abstract class ApiController
{
    protected $config;
    protected $_model;

    function __construct() 
    {
        $this->headers();

        $this->config = include CONFIG_PATH . 'config.php';

        if ($this->config['debug_mode'] == false)
            set_exception_handler([$this, 'exception_handler']);

        if ($this->config['enabled_auth']){
            $auth = new AuthController();
            $auth->check_auth();	
        }    

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
        Factory::response()->sendError($e->getMessage());
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
        $conn = Database::getConnection($this->config['database']);

        $model    = 'simplerest\\models\\'.$this->_model;
        $instance = new $model($conn); 
    
        $_get   = Factory::request()->getQuery();
    
        $fields = Arrays::shift($_get,'fields');
        $fields = $fields != NULL ? explode(',',$fields) : NULL;
        
        ///
        $exclude = Arrays::shift($_get,'exclude');
        $exclude = $exclude != NULL ? explode(',',$exclude) : NULL;

        if ($exclude != null)
            $instance->hide($exclude);

        if ($id != null)
        {
            // one instance by id
            $instance->id = $id; 
            if ($instance->fetch($fields) === false)
                Factory::response()->sendCode(404);
            else
                Factory::response()->send($instance);
        }else{    
            // "list
            $limit  = (int) Arrays::shift($_get,'limit');
            $offset = (int) Arrays::shift($_get,'offset',0);
            $order  = Arrays::shift($_get,'order');

            try {
                if (!empty($_get)){
                    $rows = $instance->filter($fields, $_get, $order, $limit, $offset);
                    Factory::response()->code(empty($rows) ? 404 : 200)->send($rows); 
                }else {
                    $rows = $instance->fetchAll($fields, $order, $limit, $offset);
                    Factory::response()->code(empty($rows) ? 404 : 200)->send($rows); 
                }	
            } catch (\Exception $e) {
                Factory::response()->sendError('Error in fetch: '.$e->getMessage());
            }	
        }
    } // end method

    function post(){
        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $model    = '\\simplerest\\models\\'.$this->_model;
        $instance = new $model();

        $missing = $instance::diffWithSchema($data, ['id']);
        if (!empty($missing))
            Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));
    
        $conn = Database::getConnection($this->config['database']);
        $instance->setConn($conn);

        if ($instance->create($data)!==false){
            Factory::response()->send(['id' => $instance->id], 201);
        }	
        else
            Factory::response()->sendError("Error: creation of resource fails!");
    } // end method
    
        
    function put($id = null){
        if ($id == null)
            Factory::response()->code(400)->sendError("Lacks id in request");

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $model    = 'simplerest\\models\\'.$this->_model;
        $instance = new $model();
        $instance->id = $id;

        $missing = $instance::diffWithSchema($data, ['id']);
        if (!empty($missing))
            Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing));
        
        $conn = Database::getConnection($this->config['database']);
        $instance->setConn($conn);

        $instance->id = $id;
        if (!$instance->exists()){
            Factory::response()->code(404)->sendError("Register for id=$id does not exists");
        }
        
        try {

            if($instance->update($data)!==false)
                Factory::response()->sendJson("OK");
            else
                Factory::response()->sendError("Error in UPDATE");

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during update for id=$id with message: {$e->getMessage()}");
        }
    } // end method
    
        
    function delete($id = NULL){
        if($id == NULL)
            Factory::response()->sendError("Lacks id in request",400);

        $conn = Database::getConnection($this->config['database']);
        
        $model    = 'simplerest\\models\\'.$this->_model;
        $instance = new $model($conn);
        $instance->id = $id;

        if($instance->delete()){
            Factory::response()->sendJson("OK");
        }	
        else
            Factory::response()->sendError("Record not found",404);
    } // end method

    
    function patch($id = NULL){ 
        if ($id == null)
            Factory::response()->sendError("Lacks id in request",400);

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $conn = Database::getConnection($this->config['database']);

        $model    = 'simplerest\\models\\'.$this->_model;
        $instance = new $model($conn);
        $instance->id = $id;

        try {
            if($instance->update($data)!==false)
                Factory::response()->sendJson("OK");
            else
                Factory::response()->sendError("Error in PATCH",404);	
        } catch (\Exception $e) {
            Factory::response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
        }
    } // end method
                
    
}    