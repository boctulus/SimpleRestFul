<?php

namespace simplerest\core;

use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Database;
use simplerest\models\GroupPermissionsModel;
use simplerest\models\OtherPermissionsModel;
use simplerest\models\FoldersModel;

abstract class ApiController extends Controller
{
    protected $scope;
    protected $callable = [];
    protected $config;
    protected $_model;
    protected $model_table;
    protected $auth_payload = null;
    protected $uid;
    protected $is_admin;
    protected $role;
    protected $folder_field;
    protected $guest_root_access = false;
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

        if ($this->config['enabled_auth']){ //       
            #if ($auth_object == null)
            #    $auth_object = new \simplerest\controllers\AuthController();

            $operations = [ 
                'read'   => ['get'],
                'create' => ['post'],
                'update' => ['put', 'patch'],
                'delete' => ['delete'],
                'write'  => ['post', 'put', 'patch', 'delete']
            ];           

            $this->auth_payload = $auth_object->check();

            if (!empty($this->auth_payload)){
                $this->uid = $this->auth_payload->uid;
                $this->is_admin = $this->auth_payload->is_admin;
                $this->role  = $this->auth_payload->user_role;
            }else{
                $this->uid = null;
                $this->is_admin = false;
                $this->role = 'guest';
            }

            $cruds = $this->scope[$this->role];

            if (!is_null($this->scope[$this->role])){
                foreach ($operations as $op => $verbs) {
                    if (in_array($op, $cruds))
                        $this->callable = array_merge($this->callable, $verbs);
                }
            }    

            if (empty($this->callable))
                Factory::response()->sendError('Authorization not found !',400);

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

    
    /**
     * setheaders
     * mover a Response *
     *
     * @param  mixed $headers
     *
     * @return void
     */
    private function setheaders(array $headers = []) {
        $headers = array_merge($this->default_headers, $headers);     

        foreach ($headers as $k => $val){
            if (empty($val))
                continue;
            
            header("${k}:$val");
        }
    }

    /**
     * exception_handler
     *
     * @param  mixed $e
     *
     * @return void
     */
    function exception_handler($e) {
        Factory::response()->sendError($e->getMessage());
    }

    
    /**
     * head
     * discard conentent (body)
     * 
     * @param  mixed $id
     *
     * @return void
     */
    function head(int $id = null) {
        if (method_exists($this,'get')){
            ob_start();
            $this->get($id);
            ob_end_clean();
        }
    }

    /**
     * options
     *
     * @return void
     */
    function options(){
    }

 
    /**
     * hasPerm
     *
     * @param  int    $folder
     * @param  object $conn
     * @param  string $operation
     *
     * @return bool
     */
    protected function hasPerm(int $folder, object $conn, string $operation)
    {
        if ($operation != 'r' && $operation != 'w')
            throw new \InvalidArgumentException("Permissions are 'r' or 'w' but not '$operation'");

        $o = new OtherPermissionsModel($conn);

        $rows = $o->filter(null, ['folder_id', $folder]);

        $r = $rows[0]['r'] ?? null;
        $w = $rows[0]['w'] ?? null;

        if ($this->role == 'guest'){
            $r = $r && $rows[0]['guest'];
            $w = $w && $rows[0]['guest'];
        }

        if (($operation == 'r' && $r) || ($operation == 'w' && $w)) {
            return true;
        }
        
        $g = new GroupPermissionsModel($conn);
        $rows = $g->filter(null, [
                                    ['folder_id', $folder], 
                                    ['member', $this->uid]
        ]);

        $r = $rows[0]['r'] ?? null;
        $w = $rows[0]['w'] ?? null;

        if (($operation == 'r' && $r) || ($operation == 'w' && $w)) {
            return true;
        }

        return false;
    }

    /**
     * get
     *
     * @param  mixed $id
     *
     * @return void
     */
    function get(int $id = null){
        try {
            
            $conn = Database::getConnection();

            $model    = 'simplerest\\models\\'.$this->_model;
            $instance = new $model($conn); 
            
            $_get  = Factory::request()->getQuery();
            
            $fields = Arrays::shift($_get,'fields');
            $fields = $fields != NULL ? explode(',',$fields) : NULL;

            $exclude = Arrays::shift($_get,'exclude');
            $exclude = $exclude != NULL ? explode(',',$exclude) : NULL;

            if ($exclude != null)
                $instance->hide($exclude);
            
            $folder = Arrays::shift($_get,'folder');

            foreach ($_get as $key => $val){
                if ($val == 'NULL' || $val == 'null'){
                    $_get[$key] = NULL;
                }               
            }

            //var_dump($_get);
            //exit;
        
            if ($folder !== null)
            {
                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
        
                if (!$ok || $f->resource_table!=$this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                $folder_access = $f->belongs_to == $this->uid  || $this->hasPerm($folder, $conn, 'r');    
                if (!$folder_access)
                    Factory::response()->sendError("You don't have permission for the folder $folder", 403);
            }    

            if ($id != null)
            {
                $_get = [
                    ['id', $id]
                ];  

                if (empty($folder)){               
                    // root, by id
                    if (!$this->is_admin)
                        $_get[] = ['belongs_to', $this->uid];
                }else{
                    // folder, by id
                    if (empty($this->folder_field))
                        Factory::response()->sendError("folder_field is undefined", 403);
                    
                    if ($this->role == 'guest' && !$folder_access)
                        Factory::response()->send([]);    
                        
                    $_get[] = [$this->folder_field, $f->value];
                    $_get[] = ['belongs_to', $f->belongs_to];
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

                if ($limit !=0)
                    $limit = min($limit, $this->config['max_records']);
                else    
                    $limit = $this->config['max_records'];

                // Importante:
                $_get = Arrays::nonassoc($_get);

                $allops = ['eq', 'gt', 'gteq', 'lteq', 'lt', 'neq'];
                $eqops  = ['=',  '>' , '>=',   '<=',   '<',  '!=' ];

                foreach ($_get as $key => $val){
                    if (is_array($val)){

                        $campo = $val[0];
                        //var_dump($val[1]); exit; //                         

                        if (is_array($val[1])){                             

                            foreach ($val[1] as $op => $v){
                                switch ($op) {
                                    case 'contains':
                                        unset($_get[$key]);
                                        $_get[] = [$campo, '%'.$v.'%', 'like'];
                                    break;
                                    case 'startswith':
                                        unset($_get[$key]);
                                        $_get[] = [$campo, $v.'%', 'like'];
                                    break;
                                    case 'endswith':
                                        unset($_get[$key]);
                                        $_get[] = [$campo, '%'.$v, 'like'];
                                    break;
                                    case 'in':                                         
                                        if (strpos($v, ',')!== false){    
                                            $vals = explode(',', $v);
                                            unset($_get[$key]);
                                            $_get[] = [$campo, $vals, 'IN']; 
                                        }                                         
                                    break;
                                    case 'notin':
                                        if (strpos($v, ',')!== false){    
                                            $vals = explode(',', $v);
                                            unset($_get[$key]);
                                            $_get[] = [$campo, $vals, 'NOT IN'];
                                        }                                         
                                    break;
                                    case 'between':
                                        if (substr_count($v, ',') == 1){    
                                            $vals = explode(',', $v);
                                            unset($_get[$key]);

                                            $min = min($vals[0],$vals[1]);
                                            $max = max($vals[0],$vals[1]);

                                            $_get[] = [$campo, $min, '>='];
                                            $_get[] = [$campo, $max, '<='];
                                        }                                         
                                    break;
                                    default:
                                        // 'eq', 'gt', ...
                                        $op = array_keys($val[1])[0];
                                        $v  = array_values($val[1])[0];

                                        foreach ($allops as $ko => $oo){
                                            if ($op == $oo){
                                                $op = $eqops[$ko];
                                                unset($_get[$key]);
                                                $_get[] = [$campo, $v, $op];                             
                                                break;                                    
                                            }                                    
                                        }
                                    break;
                                }
                            }
                            
                        }else{
                            // IN
                            $v = $val[1];
                            if (strpos($v, ',')!== false){    
                                $vals = explode(',', $v);
                                unset($_get[$key]);
                                $_get[] = [$campo, $vals];                                
                            } 
                        }   
                        
                    }                           
                }
          
                if (empty($folder)){
                    // root, sin especificar folder ni id (lista)
                    if ($this->role=='guest'){
                        if (!$this->guest_root_access)
                            Factory::response()->send([]);
                        else
                            $_get[] =  [$this->folder_field, NULL];        
                    }else
                        if (!$this->is_admin)
                            $_get[] = ['belongs_to', $this->uid];        
                }else{
                    // folder, sin id
                    if (empty($this->folder_field))
                        Factory::response()->sendError("'folder_field' is undefined", 403);
                  
                    if ($this->role == 'guest' && !$folder_access)
                        Factory::response()->send([]); 

                    $_get[] = [$this->folder_field, $f->value];
                    $_get[] = ['belongs_to', $f->belongs_to];
                }

                //var_dump($_get); ////
                //var_export($_get); 

                if (!empty($_get)){
                    $rows = $instance->filter($fields, $_get, null, $order, $limit, $offset);
                    Factory::response()->code(200)->send($rows); 
                }else {
                    $rows = $instance->fetchAll($fields, $order, $limit, $offset);
                    Factory::response()->code(200)->send($rows); 
                }	
        
            }

        } catch (\Exception $e) {
            Factory::response()->sendError($e->getMessage());
        }	    
    } // 


    /**
     * post
     *
     * @return void
     */
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
            $conn = Database::getConnection();
            $instance->setConn($conn);

            $data['belongs_to'] = ($this->role == 'guest' ? -1 : $this->uid); 
        
            if ($folder !== null)
            {
                if (empty($this->folder_field))
                    Factory::response()->sendError("'folder_field' is undefined", 403);

                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
        
                if (!$ok || $f->resource_table!=$this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if ($f->belongs_to != $this->uid  && !$this->hasPerm($folder, $conn, 'w'))
                    Factory::response()->sendError("You don't have permission for the folder $folder", 403);

                unset($data['folder']);    
                $data[$this->folder_field] = $f->value;
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
    
        
    /**
     * put
     *
     * @param  int $id
     *
     * @return void
     */
    function put(int $id = null){
        if ($id == null)
            Factory::response()->code(400)->sendError("Lacks id in request");

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $model    = 'simplerest\\models\\'.$this->_model;
        $instance = new $model();
        $instance->id = $id;
        $has_modified = $instance->inSchema(['modified']);
        $missing = $instance->diffWithSchema($data, ['id', 'belongs_to']);

        var_dump($has_modified);

        if (!empty($missing))
            Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing), 400);

        $folder = $data['folder'] ?? null;    

        try {
            $conn = Database::getConnection();
            $instance->setConn($conn);

            $instance->id = $id;
            $rows = $instance->filter(null, ['id', $id]);

            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists");
            }

            $data['belongs_to'] = $this->uid; //

            if ($has_modified){
                $d = new \DateTime();
                $data['modified'] = $d->format('Y-m-d G:i:s');
            }                

            if ($folder !== null)
            {
                if (empty($this->folder_field))
                    Factory::response()->sendError("'folder_field' is undefined", 403);

                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
        
                if (!$ok || $f->resource_table!=$this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if (!$this->hasPerm($folder, $conn, 'w'))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);

                unset($data['folder']);    
                $data[$this->folder_field] = $f->value;
                $data['belongs_to'] = $f->belongs_to;    

            }else{
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
    

    /**
     * patch
     *
     * @param  mixed $id
     *
     * @return void
     */
    function patch($id = NULL)
    { 
        if ($id == null)
            Factory::response()->sendError("Lacks id in request",400);

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $folder = $data['folder'] ?? null; 

        try {
            $conn = Database::getConnection();
            $model    = 'simplerest\\models\\'.$this->_model;

            $instance = new $model($conn);
            $instance->id = $id;
            $has_modified = $instance->inSchema(['modified']);

            $rows = $instance->filter(null, ['id', $id]);
            
            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists");
            }

            $data['belongs_to'] = $this->uid; //

            if ($has_modified){
                $d = new \DateTime();
                $data['modified'] = $d->format('Y-m-d G:i:s');
            }  

            if ($folder !== null)
            {
                if (empty($this->folder_field))
                    Factory::response()->sendError("'folder_field' is undefined", 403);

                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
        
                if (!$ok || $f->resource_table!=$this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if (!$this->hasPerm($folder, $conn, 'w'))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);

                unset($data['folder']);    
                $data[$this->folder_field] = $f->value;
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

        
    /**
     * delete
     *
     * @param  mixed $id
     *
     * @return void
     */
    function delete($id = NULL){
        if($id == NULL)
            Factory::response()->sendError("Lacks id in request",405);

        $data = Factory::request()->getBody();        
        $folder = $data['folder'] ?? null;

        try {    
            $conn = Database::getConnection();
        
            $model    = 'simplerest\\models\\'.$this->_model;
            $instance = new $model($conn);
            $instance->id = $id;

            $rows = $instance->filter(null, ['id', $id]);
            
            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists");
            }

            if ($folder !== null)
            {
                if (empty($this->folder_field))
                    Factory::response()->sendError("'folder_field' is undefined", 403);

                $f = new FoldersModel($conn);
                $f->id = $folder;    
                $ok = $f->fetch();
        
                if (!$ok || $f->resource_table!=$this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if (!$this->hasPerm($folder, $conn, 'w'))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);

                unset($data['folder']);    
                $data[$this->folder_field] = $f->value;
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