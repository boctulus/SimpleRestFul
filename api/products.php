<?php
declare(strict_types=1);

//namespace restful;

header('access-control-allow-credentials: true');
header('access-control-allow-headers: AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2'); 
header('access-control-allow-Methods: GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS'); 
header('access-control-allow-Origin: *');
header('content-type: application/json; charset=UTF-8');

require_once 'config/constants.php';
require_once 'auth/auth_check.php';
require_once 'libs/database.php';
include_once 'helpers/debug.php';
include_once 'helpers/arrays.php';
include_once 'helpers/messages.php';
include_once 'controllers/api_restful.php';


class ProductsController extends ApiRestfulController
{
    protected $config;
    
    function __construct()
    {
        parent::__construct();
    }

    // GET
    function get(int $id = null){
        try {
            $conn = Database::getConnection($this->config['database']);
            $product = new ProductModel($conn);
        
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
                } catch (Exception $e) {
                    response()->sendError('Error in fetch: '.$e->getMessage());
                }	
            }

        } catch (Exception $error) {
            response()->sendError($error->getMessage());
        }
    } // end method

    function post(){
        try {
            $data = request()->getBody();

            if (empty($data))
                response()->sendError('Invalid JSON',400);
            
            $product = new ProductModel();
    
            $missing = $product::diffWithSchema($data, ['id']);
            if (!empty($missing))
                response()->sendError('Lack some properties in your request: '.implode(',',$missing));
        
            $conn = Database::getConnection($this->config['database']);
            $product->setConn($conn);
    
            if ($product->create($data)!==false){
                response()->send(['id' => $product->id], 201);
            }	
            else
                response()->sendError("Error: creation of resource fails!");
    
        } catch (Exception $error) {
            response()->sendError($error->getMessage());
        }
    } // end method
    
        
    function put($id = null){
        try {
            if ($id == null)
                response()->code(400)->sendError("Lacks id in request");

            $data = request()->getBody();

            if (empty($data))
                response()->sendError('Invalid JSON',400);
            
            $product = new ProductModel();
            $product->id = $id;

            $missing = $product::diffWithSchema($data, ['id']);
            if (!empty($missing))
                response()->sendError('Lack some properties in your request: '.implode(',',$missing));
            
            $conn = Database::getConnection($this->config['database']);
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

        } catch (Exception $error) {
            response()->sendError($error->getMessage());
        }
    } // end method
    
        
    function delete($id = NULL){
        try {
            if($id == NULL)
                response()->sendError("Lacks id in request",400);

            $conn = Database::getConnection($this->config['database']);
            $product = new ProductModel($conn);
            $product->id = $id;

            if($product->delete()){
                response()->sendJson("OK");
            }	
        else
            response()->sendError("Record not found",404);

        } catch (Exception $error) {  // extender la clase y loguear este tipo de errores (500)
            response()->sendError($error->getMessage());
        }
    } // end method

    
    function patch($id = NULL){ 
        try {
            if ($id == null)
                response()->sendError("Lacks id in request",400);

            $data = request()->getBody();

            if (empty($data))
                response()->sendError('Invalid JSON',400);
            
            $conn = Database::getConnection($this->config['database']);

            $product = new ProductModel($conn);
            $product->id = $id;

            try {
                if($product->update($data)!==false)
                    response()->sendJson("OK");
                else
                    response()->sendError("Error in PATCH",404);	
            } catch (Exception $e) {
                response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
            }
        } catch (Exception $error) {
            response()->sendError($error->getMessage());
        }
    } // end method
                
    
        
    
} // end class
