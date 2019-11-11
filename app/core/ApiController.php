<?php

namespace simplerest\core;

use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Database;
use simplerest\libs\Debug;
use simplerest\models\GroupPermissionsModel;
use simplerest\models\OtherPermissionsModel;
use simplerest\models\FoldersModel;
use simplerest\models\RolesModel;
use simplerest\libs\Validator;


abstract class ApiController extends Controller
{
    protected $scope;
    protected $callable = [];
    protected $config;
    protected $modelName;
    protected $model_table;
    protected $soft_delete;
    protected $auth_payload = null;
    protected $uid;
    protected $is_admin = false;
    protected $roles = [];
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
                //Debug::debug($this->uid, 'UID:');

                $r = new RolesModel();
                $this->roles  = $this->auth_payload->roles;              

                foreach ($this->roles as $role){
                    if ($r->is_admin($role)){
                        $this->is_admin = true;
                        break;
                    }
                }
            }else{
                $this->uid = null;
                $this->is_admin = false;
                $this->roles = ['guest'];
            }

            //var_export($this->roles);
            //exit;

            // y si ya se que es admin....
            if ($this->is_admin){
                $this->callable = ['get', 'post', 'put', 'patch', 'delete'];
            }else{
                foreach ($this->roles as $role){
                    $cruds = $this->scope[$role];
    
                    if (!empty($this->scope[$role])){
                        foreach ($operations as $op => $verbs) {
                            if (in_array($op, $cruds))
                                $this->callable = array_merge($this->callable, $verbs);
                        }
                    } 
                }    
            }

            //var_export($this->callable);

            if (empty($this->callable))
                Factory::response()->sendError('You are not authorized',403);

            $this->callable = array_merge($this->callable,['head','options']);
    
            // headers
            $verbos = array_merge($this->callable, ['options']);            
            $headers = array_merge($headers, ['access-control-allow-Methods' => implode(',',array_map( function ($e){ return strtoupper($e); },$verbos)) ]);
            $this->setheaders($headers);            
        }    

        if (preg_match('/([A-Z][a-z0-9_]+[A-Z]*[a-z0-9_]*[A-Z]*[a-z0-9_]*[A-Z]*[a-z0-9_]*)/', get_called_class(), $matchs)){
            $this->modelName = $matchs[1] . 'Model';
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

    protected function is_guest(){
        return (count($this->roles) == 1 && $this->roles[0] == 'guest');
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

        $rows = $o->where(['folder_id', $folder])->get();

        $r = $rows[0]['r'] ?? null;
        $w = $rows[0]['w'] ?? null;

        if ($this->is_guest()){
            $r = $r && $rows[0]['guest'];
            $w = $w && $rows[0]['guest'];
        }

        if (($operation == 'r' && $r) || ($operation == 'w' && $w)) {
            return true;
        }
        
        $g = new GroupPermissionsModel($conn);
        $rows = $g->where([
                                    ['folder_id', $folder], 
                                    ['member', $this->uid]
        ])->get();

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

            $model    = 'simplerest\\models\\'.$this->modelName;
            $instance = new $model($conn); 
            
            $_get    = Factory::request()->getQuery();

            // patch for Nginx
            $_q      = Arrays::shift($_get,'q');
            
            $fields  = Arrays::shift($_get,'fields');
            $fields  = $fields != NULL ? explode(',',$fields) : NULL;

            $properties = $instance->getProperties();
            foreach ((array) $fields as $field){
                if (!in_array($field,$properties))
                    Factory::response()->sendError("Unknown field '$field'", 400);
            }

            $exclude = Arrays::shift($_get,'exclude');
            $exclude = $exclude != NULL ? explode(',',$exclude) : NULL;

            foreach ((array) $exclude as $field){
                if (!in_array($field,$properties))
                    Factory::response()->sendError("Unknown field '$field' in exclude", 400);
            }

            if ($exclude != null)
                $instance->hide($exclude);
                       
            $pretty  = Arrays::shift($_get,'pretty');
            $folder  = Arrays::shift($_get,'folder');

            foreach ($_get as $key => $val){
                if ($val == 'NULL' || $val == 'null'){
                    $_get[$key] = NULL;
                }               
            }

            //var_dump($_get);
            //exit;
        
            if ($folder !== null)
            {
                $f = Database::table('folders');
                $f_rows = $f->where(['id' => $folder])->get();
        
                if (count($f_rows) == 0 || $f_rows[0]['resource_table'] != $this->model_table)
                    Factory::response()->sendError('Folder not found', 404);  
        
                $folder_access = $f_rows[0]['belongs_to'] == $this->uid  || $this->hasPerm($folder, $conn, 'r');    
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
                    
                    if ($this->is_guest() && !$folder_access)
                        Factory::response()->send([]);    
                        
                    $_get[] = [$this->folder_field, $f->value];
                    $_get[] = ['belongs_to', $f->belongs_to];
                }

                $rows = $instance->where($_get)->get($fields); 
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

                        if (is_array($val[1])){                             

                            foreach ($val[1] as $op => $v){
                                switch ($op) {
                                    case 'contains':
                                        $_get[$key] = [$campo, '%'.$v.'%', 'like'];
                                    break;
                                    case 'startsWith':
                                        $_get[$key] = [$campo, $v.'%', 'like'];
                                    break;
                                    case 'endsWith':
                                        $_get[$key] = [$campo, '%'.$v, 'like'];
                                    break;
                                    case 'in':                                         
                                        if (strpos($v, ',')!== false){    
                                            $vals = explode(',', $v);
                                            $_get[$key] = [$campo, $vals, 'IN']; 
                                        }                                         
                                    break;
                                    case 'notIn':
                                        if (strpos($v, ',')!== false){    
                                            $vals = explode(',', $v);
                                            $_get[$key] = [$campo, $vals, 'NOT IN'];
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

                                        $found = false;
                                        foreach ($allops as $ko => $oo){
                                            if ($op == $oo){
                                                $op = $eqops[$ko];
                                                $_get[$key] = [$campo, $v, $op]; 
                                                $found = true;                            
                                                break;                                    
                                            }                                    
                                        }

                                        if (!$found)
                                            Factory::response()->sendError("Invalid operator '$op'", 400);
                                    break;
                                }
                            }
                            
                        }else{                           

                            // IN
                            $v = $val[1];
                            if (strpos($v, ',')!== false){    
                                $vals = explode(',', $v);
                                $_get[$key] = [$campo, $vals];                                
                            } 
                        }   
                        
                    }else {
                        // ???
                    }                           
                }
          
                 // Si se pide algo que involucra un campo no está en el schema lanzar error
                 foreach ($_get as $arr){
                     if (!in_array($arr[0],$properties))
                         Factory::response()->sendError("Unknown field '$arr[0]'", 400);
                 }
                  
                if (empty($folder)){
                    // root, sin especificar folder ni id (lista)
                    if ($this->is_guest()){
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
                  
                    if ($this->is_guest() && !$folder_access)
                        Factory::response()->send([]); 

                    $_get[] = [$this->folder_field, $f_rows[0]['value']];
                    $_get[] = ['belongs_to', $f_rows[0]['belongs_to']];
                }
           
                if (strtolower($pretty) == 'false' || $pretty === 0)
                    $pretty = false;
                else
                    $pretty = true;   

                Factory::response()->setPretty($pretty);

                // Lo "malo" de delegar la validación al modelo es que devuelve error 500 en vez de 400 si falla
                $instance->setValidator(new Validator());

                if (!empty($_get)){                    
                    $rows = $instance->where($_get)->get($fields, $order, $limit, $offset);
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
        
        $model    = '\\simplerest\\models\\'.$this->modelName;
        $instance = new $model();

        $folder = $data['folder'] ?? null;

        try {
            $conn = Database::getConnection();
            $instance->setConn($conn);

            if ($instance->inSchema(['belongs_to']))
                $data['belongs_to'] = ($this->is_guest() ? -1 : $this->uid); 
        
            if ($folder !== null)
            {
                if (empty($this->folder_field))
                    Factory::response()->sendError("'folder_field' is undefined", 403);

                $f = Database::table('folders');
                $f_rows = $f->where(['id' => $folder])->get();
                      
                if (count($f_rows) == 0 || $f_rows[0]['resource_table'] != $this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if (!$this->hasPerm($folder, $conn, 'w'))
                    Factory::response()->sendError("You have not permission for the folder $folder", 403);

                unset($data['folder']);    
                $data[$this->folder_field] = $f_rows[0]['value'];
                $data['belongs_to'] = $f_rows[0]['belongs_to'];    
            }    

            $validado = (new Validator)->validate($instance->getRules(), $data);
            if ($validado !== true){
                Factory::response()->sendError('Data validation error:', 400, $validado);
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
    
    protected function modify($id = NULL, bool $put_mode = false)
    { 
        if ($id == null)
            Factory::response()->sendError("Lacks id in request",400);

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $folder = $data['folder'] ?? null; 

        $model    = 'simplerest\\models\\'.$this->modelName;
        $instance = new $model();

        try {
            $conn = Database::getConnection();
            $instance->setConn($conn); 

            $rows = $instance->where(['id' => $id])->get();
            
            // Creo otra nueva instancia
            $instance = new $model();
            $instance->setConn($conn);

            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists");
            }

            if (!$this->is_admin){
                if (isset($data['belongs_to']))
                    unset($data['belongs_to']);

                if (isset($data['deleted_at']))
                    unset($data['deleted_at']);
            }else{
                $instance->fill(['deleted_at']);
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
                    Factory::response()->send('You are not the owner', 403);
                }
            }        

            foreach ($data as $k => $v){
                if (strtoupper($v) == 'NULL' && $instance->isNullable($k)) 
                    $data[$k] = NULL;
            }

            $validado = (new Validator())->setRequired($put_mode)->validate($instance->getRules(), $data, null);
            if ($validado !== true){
                Factory::response()->sendError('Data validation error:', 400, $validado);
            }
    
            if ($instance->where(['id', $id])->update($data) !== false)
                Factory::response()->sendJson("OK");
            else
                Factory::response()->sendError("Error in PATCH",404);	

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
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
        $this->modify($id, true);
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
        $this->modify($id);
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
        
            $model    = 'simplerest\\models\\'.$this->modelName;
            
            $instance = new $model($conn);
            $instance->fill(['deleted_at']); //

            $rows = $instance->where(['id', $id])->get();
            
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

            if ($this->is_admin){
                if ($instance->inSchema(['locked'])){
                    $instance->fill(['locked'])->update(['locked' => 1]);
                }   
            }else {
                if (isset($rows[0]['locked']) && $rows[0]['locked'] == 1){
                    Factory::response()->sendError("Locked by Admin", 403);
                }
            }
       
            if($instance->delete($this->soft_delete && $instance->inSchema(['deleted_at']) )){
                Factory::response()->sendJson("OK");
            }	
            else
                Factory::response()->sendError("Record not found",404);

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during DELETE for id=$id with message: {$e->getMessage()}");
        }

    } // 
       
    
}  