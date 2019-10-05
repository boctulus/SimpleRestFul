<?php

namespace simplerest\core;

use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Database;
use simplerest\models\GroupPermissionsModel;
use simplerest\models\OtherPermissionsModel;
use simplerest\models\RolesModel;
use simplerest\models\FoldersModel;

abstract class ApiController extends Controller
{
    // ALC   
    protected $scope = [
        'guest' => [ ],
        'basic' => ['read'],
        'regular' => ['read', 'write'],
        'admin' => ['read', 'write']
    ];

    protected $callable = [];
    protected $config;
    protected $_model;
    protected $model_table;
    protected $auth_payload = null;
    protected $uid;
    protected $is_admin;
    protected $default_headers = [
        'access-control-allow-Methods' => 'GET,HEAD,PUT,PATCH,POST,DELETE,OPTIONS',
        'access-control-allow-credentials' => 'true',
        'access-control-allow-headers' => 'AccountKey,x-requested-with, Content-Type, origin, authorization, accept, client-security-token, host, date, cookie, cookie2',
        'content-type' => 'application/json; charset=UTF-8',
    ];

    function __construct(array $headers = [], IAuth $auth_object = null) 
    {        
        $this->config = include CONFIG_PATH . 'config.php';

        if ($this->config['debug_mode'] == false)
            set_exception_handler([$this, 'exception_handler']);

        if ($this->config['enabled_auth']){        
            if ($auth_object == null)
                $auth_object = new \simplerest\controllers\AuthController();

            $operations = [ 
                'read' => ['get'],
                'create' => ['post'],
                'update' => ['put', 'patch'],
                'delete' => ['delete'],
                'write'  => ['post', 'put', 'patch', 'delete']
            ];           

            $this->auth_payload = $auth_object->check_auth();
            $this->uid = $this->auth_payload->uid;

            // roles and scope
            $this->is_admin = $this->auth_payload->is_admin;
            $cruds = $this->scope[$this->auth_payload->user_role];

            foreach ($operations as $op => $verbs) {
                if (in_array($op, $cruds))
                    $this->callable = array_merge($this->callable, $verbs);
            }

            $this->callable = array_merge($this->callable,['head','options']);
    
            // headers
            $verbos = array_merge($this->callable, ['options']);            
            $headers = array_merge($headers, ['access-control-allow-Methods' => implode(',',array_map( function ($e){ return strtoupper($e); },$verbos)) ]);
            $this->setheaders($headers);            
        }    

        if (preg_match('/([A-Z][a-z0-9_]+[A-Z]*[a-z0-9_]*[A-Z]*[a-z0-9_]*[A-Z]*[a-z0-9_]*)/', get_called_class(), $matchs)){
            $this->_model = $matchs[1] . 'Model';
            $this->model_table = strtolower($matchs[1]);
        }    
    }

    // mover a Response
    private function setheaders(array $headers = []) {
        $headers = array_merge($this->default_headers, $headers);     

        foreach ($headers as $k => $val){
            if (empty($val))
                continue;
            
            header("${k}:$val");
        }
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
        
        $_get  = Factory::request()->getQuery();

        foreach ($_get as $key => $val){
            if ($val == 'NULL' || $val == 'null'){
                $_get[$key] = NULL;
            }                
        }

        $fields = Arrays::shift($_get,'fields');
        $fields = $fields != NULL ? explode(',',$fields) : NULL;
        
        ///
        $exclude = Arrays::shift($_get,'exclude');
        $exclude = $exclude != NULL ? explode(',',$exclude) : NULL;

        if ($exclude != null)
            $instance->hide($exclude);
         
        ////////////////////////////////////////
        $folder = Arrays::shift($_get,'folder');

        if (!empty($folder)){
            $f = new FoldersModel($conn);
            $f->id = $folder;    
            $ok = $f->fetch(['field', 'value']);

            if (!$ok)
                Factory::response()->sendError('Folder not found', 404);  

            $g = new GroupPermissionsModel($conn);
            $seek = [
                ['folder_id', $folder], 
                ['member', $this->uid]
            ];
            $rows = $g->filter(null, $seek);

            if (empty($rows))
                Factory::response()->sendError("You have not permission for the folder $folder", 403);  

            $gr = $rows[0]['r'];
            $gw = $rows[0]['w'];

            if (!$gr)
                Factory::response()->sendError("You have not permission for the folder $folder", 403);
        }        
        ////////////////////////////////////////  

        //var_dump($_get);

        if ($id != null)
        {
            $_get = [
                ['id', $id]
            ];    
               
            if (empty($folder)){               
                // User permissions
                if (!$this->is_admin)
                    $_get[] = ['belongs_to', $this->uid];
            }else{
                $_get[] = [$f->field, $f->value];
            }

            $rows = $instance->filter($fields, $_get); 
            if (empty($rows))
                Factory::response()->sendCode(404);
            else
                Factory::response()->send($rows[0]);
        }else{    
            // "list
            $limit  = (int) Arrays::shift($_get,'limit');
            $offset = (int) Arrays::shift($_get,'offset',0);
            $order  = Arrays::shift($_get,'order');

            // Importante:
            $_get = Arrays::nonassoc($_get);     
                
            if (empty($folder)){             
                // User permissions
                if (!$this->is_admin)
                    $_get[] = ['belongs_to', $this->uid];        
            }else{
                $_get[] = [$f->field, $f->value];
            }
                    
            try {
                if (!empty($_get)){
                    $rows = $instance->filter($fields, $_get, null, $order, $limit, $offset);
                    Factory::response()->code(200)->send($rows); 
                }else {
                    $rows = $instance->fetchAll($fields, $order, $limit, $offset);
                    Factory::response()->code(200)->send($rows); 
                }	
            } catch (\Exception $e) {
                Factory::response()->sendError($e->getMessage());
            }	
        }
    } // 

    function post(){
        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $model    = '\\simplerest\\models\\'.$this->_model;
        $instance = new $model();
        $missing = $instance->diffWithSchema($data, ['id', 'belongs_to']);

        if (!empty($missing))
            Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing), 400);
    
        $folder = $data['folder'] ?? null;

        try {
            $conn = Database::getConnection($this->config['database']);
            $instance->setConn($conn);

            $data['belongs_to'] = $this->uid; //
        
            if (!empty($folder)){
                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
    
                if (!$ok)
                    Factory::response()->sendError('Folder $folder not found', 404);  
    
                $g = new GroupPermissionsModel($conn);
                $seek = [
                    ['folder_id', $folder], 
                    ['member', $this->uid]
                ];
                $rows = $g->filter(null, $seek);
    
                if (empty($rows))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);  
                $gr = $rows[0]['r'];
                $gw = $rows[0]['w'];
    
                if (!$gw)
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);
           
                unset($data['folder']);    
                $data[$f->field] = $f->value;
                $data['belongs_to'] = $f->belongs_to;
            }        

            if ($instance->create($data)!==false){
                Factory::response()->send(['id' => $instance->id], 201);
            }	
            else
                Factory::response()->sendError("Error: creation of resource fails!");

        } catch (\Exception $e) {
            Factory::response()->sendError($e->getMessage());
        }	

    } // 
    
        
    function put($id = null){
        if ($id == null)
            Factory::response()->code(400)->sendError("Lacks id in request");

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $model    = 'simplerest\\models\\'.$this->_model;
        $instance = new $model();
        $instance->id = $id;
        $missing = $instance->diffWithSchema($data, ['id', 'belongs_to']);

        if (!empty($missing))
            Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing), 400);

        $folder = $data['folder'] ?? null;    

        try {
            $conn = Database::getConnection($this->config['database']);
            $instance->setConn($conn);
            $instance->id = $id;

            $rows = $instance->filter(null, ['id', $id]);

            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists");
            }

            if (!empty($folder)){
                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
    
                if (!$ok)
                    Factory::response()->sendError('Folder $folder not found', 404);  
    
                $g = new GroupPermissionsModel($conn);
                $seek = [
                    ['folder_id', $folder], 
                    ['member', $this->uid]
                ];
                $rows = $g->filter(null, $seek);
    
                if (empty($rows))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);  
                $gr = $rows[0]['r'];
                $gw = $rows[0]['w'];
    
                if (!$gw)
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);
           
                unset($data['folder']);    
                $data[$f->field] = $f->value;
                $data['belongs_to'] = $f->belongs_to;

            }else {
                if (!$this->is_admin && $rows[0]['belongs_to'] != $this->uid){
                    Factory::response()->sendCode(403);
                }
            }        
    
            if($instance->update($data)!==false)
                Factory::response()->sendJson("OK");
            else
                Factory::response()->sendError("Error in UPDATE");
        } catch (\Exception $e) {
            Factory::response()->sendError("Error during update for id=$id with message: {$e->getMessage()}");
        }

    } // 
    
    function patch($id = NULL)
    { 
        if ($id == null)
            Factory::response()->sendError("Lacks id in request",400);

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $folder = $data['folder'] ?? null; 

        try {
            $conn = Database::getConnection($this->config['database']);
            $model    = 'simplerest\\models\\'.$this->_model;

            $instance = new $model($conn);
            $instance->id = $id;

            $rows = $instance->filter(null, ['id', $id]);
            
            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists");
            }

            if (!empty($folder)){
                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
    
                if (!$ok)
                    Factory::response()->sendError('Folder $folder not found', 404);  
    
                $g = new GroupPermissionsModel($conn);
                $seek = [
                    ['folder_id', $folder], 
                    ['member', $this->uid]
                ];
                $rows = $g->filter(null, $seek);
    
                if (empty($rows))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);  
                $gr = $rows[0]['r'];
                $gw = $rows[0]['w'];
    
                if (!$gw)
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);
           
                unset($data['folder']);    
                $data[$f->field] = $f->value;
                $data['belongs_to'] = $f->belongs_to;

            }else {
                if (!$this->is_admin && $rows[0]['belongs_to'] != $this->uid){
                    Factory::response()->sendCode(403);
                }
            }        
     
            if($instance->update($data)!==false)
                Factory::response()->sendJson("OK");
            else
                Factory::response()->sendError("Error in PATCH",404);	

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
        }
    } //
        
    function delete($id = NULL){
        if($id == NULL)
            Factory::response()->sendError("Lacks id in request",405);

        $data = Factory::request()->getBody();        
        $folder = $data['folder'] ?? null;

        try {    
            $conn = Database::getConnection($this->config['database']);
        
            $model    = 'simplerest\\models\\'.$this->_model;
            $instance = new $model($conn);
            $instance->id = $id;

            $rows = $instance->filter(null, ['id', $id]);
            
            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists");
            }

            if (!empty($folder)){
                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
    
                if (!$ok)
                    Factory::response()->sendError('Folder $folder not found', 404);  
    
                $g = new GroupPermissionsModel($conn);
                $seek = [
                    ['folder_id', $folder], 
                    ['member', $this->uid]
                ];
                $rows = $g->filter(null, $seek);
    
                if (empty($rows))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);  
                $gr = $rows[0]['r'];
                $gw = $rows[0]['w'];
    
                if (!$gw)
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);
           
                unset($data['folder']);    
                $data[$f->field] = $f->value;
                $data['belongs_to'] = $f->belongs_to;

            }else {
                if (!$this->is_admin && $rows[0]['belongs_to'] != $this->uid){
                    Factory::response()->sendCode(403);
                }
            }   

            if($instance->delete()){
                Factory::response()->sendJson("OK");
            }	
            else
                Factory::response()->sendError("Record not found",404);

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
        }

    } // 
       
    
}  