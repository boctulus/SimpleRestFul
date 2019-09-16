<?php

namespace Controllers;

header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS');
header('access-control-allow-credentials: true');
header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
header('access-control-allow-Origin: *');
header('content-type: application/json; charset=UTF-8');

require_once 'config/constants.php';
require_once ROOT_PATH . 'api/auth/auth_check.php';
require_once LIBS_PATH . 'database.php';
include_once HELPERS_PATH . 'debug.php';
include_once HELPERS_PATH . 'arrays.php';
include_once HELPERS_PATH . 'messages.php';


abstract class ApiRestfulController
{
    protected $config;
    protected $_model;

    function __construct() {
        set_exception_handler([$this, 'exception_handler']);

        $this->config = include ROOT_PATH . 'config/config.php';

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
        $product = new $this->_model($conn);
    
        $_get  = request()->getQuery();
    
        $fields = shift($_get,'fields');
        $fields = $fields != NULL ? explode(',',$fields) : NULL;

        if ($id != null)
        {
            // one product by id
            $product->id = $id; 
            if ($product->fetchOne($fields) === false)
                response()->sendCode(404);
            else
                response()->send($product);
        }else{    
            // "list
            $limit  = (int) shift($_get,'limit');
            $offset = (int) shift($_get,'offset',0);
            $order  = shift($_get,'order');

            try {
                if (!empty($_get)){
                    $rows = $product->filter($fields, $_get, $order, $limit, $offset);
                    response()->code(empty($rows) ? 404 : 200)->send($rows); 
                }else {
                    $rows = $product->fetchAll($fields, $order, $limit, $offset);
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
        
        $product = new $this->_model();

        $missing = $product::diffWithSchema($data, ['id']);
        if (!empty($missing))
            response()->sendError('Lack some properties in your request: '.implode(',',$missing));
    
        $conn = \Libs\Database::getConnection($this->config['database']);
        $product->setConn($conn);

        if ($product->create($data)!==false){
            response()->send(['id' => $product->id], 201);
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
        
        $product = new $this->_model();
        $product->id = $id;

        $missing = $product::diffWithSchema($data, ['id']);
        if (!empty($missing))
            response()->sendError('Lack some properties in your request: '.implode(',',$missing));
        
        $conn = \Libs\Database::getConnection($this->config['database']);
        $product->setConn($conn);

        $product->id = $id;
        if (!$product->exists()){
            response()->code(404)->sendError("Register for id=$id does not exists");
        }
        
        try {

            if($product->update($data)!==false)
                response()->sendJson("OK");
            else
                response()->sendError("Error in UPDATE");

        } catch (Exception $e) {
            response()->sendError("Error during update for id=$id with message: {$e->getMessage()}");
        }
    } // end method
    
        
    function delete($id = NULL){
        if($id == NULL)
            response()->sendError("Lacks id in request",400);

        $conn = \Libs\Database::getConnection($this->config['database']);
        $product = new $this->_model($conn);
        $product->id = $id;

        if($product->delete()){
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

        $product = new $this->_model($conn);
        $product->id = $id;

        try {
            if($product->update($data)!==false)
                response()->sendJson("OK");
            else
                response()->sendError("Error in PATCH",404);	
        } catch (Exception $e) {
            response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
        }
    } // end method
                
    
}    