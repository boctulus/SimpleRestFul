<?php

namespace simplerest\core\api\v1;

use simplerest\core\interfaces\IAuth;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\DB;
use simplerest\libs\Debug;
use simplerest\libs\Url;
use simplerest\libs\Strings;
use simplerest\libs\Validator;
use simplerest\models\FoldersModel;
use simplerest\core\api\v1\ResourceController;
use simplerest\core\exceptions\SqlException;
use simplerest\core\exceptions\InvalidValidationException;

use simplerest\libs\Files;    

abstract class ApiController extends ResourceController
{
    static protected $folder_field;
    static protected $soft_delete = true;

    protected $is_listable;
    protected $is_retrievable;
    protected $callable = [];
    protected $config;
    protected $impersonated_by;
    protected $conn;
    protected $model_name;
    protected $model_table;
    protected $instance; // main

    protected $id;
    protected $folder;


    function __construct($auth = null) 
    {  
        parent::__construct($auth);

        if ($this->model_name != null){
            $this->model_table = Strings::fromCamelCase(Strings::removeRTrim('Model', $this->model_name));
        }else {
            if ($this->model_table != null){            
                $this->model_name = implode(array_map('ucfirst',explode('_', $this->model_table))) . 'Model';
            } elseif (preg_match('/([A-Z][a-z0-9_]+[A-Z]*[a-z0-9_]*[A-Z]*[a-z0-9_]*[A-Z]*[a-z0-9_]*)/', get_called_class(), $matchs)){
                $this->model_name = $matchs[1] . 'Model';
                $this->model_table = Strings::fromCamelCase($matchs[1]);
            } else {
                Factory::response()->sendError("ApiController with undefined Model", 500);
            }  
        }
        
        $perms = $this->getPermissions($this->model_table);
        //Debug::dd($perms, 'perms'); /////

        switch ($_SERVER['REQUEST_METHOD']) {
            case 'GET':
                if ($this->acl->hasSpecialPermission('read_all', $this->roles)){
                    $this->addCallable('get');
                    $this->is_listable    = true;
                    $this->is_retrievable = true;
                } else {
                    if ($this->acl->hasResourcePermission('show', $this->roles, $this->model_table) || 
                        $this->acl->hasResourcePermission('show_all', $this->roles, $this->model_table)){
                        $this->addCallable('get');
                        $this->is_retrievable = true;
                    }

                    if ($this->acl->hasResourcePermission('list', $this->roles, $this->model_table) ||
                        $this->acl->hasResourcePermission('list_all', $this->roles, $this->model_table)){
                        $this->addCallable('get');
                        $this->is_listable    = true;
                    }
                }  
            break;
            
            case 'POST':
                if ($this->acl->hasSpecialPermission('write_all', $this->roles)){
                    $this->addCallable('post');
                } else {
                    if ($this->acl->hasResourcePermission('create', $this->roles, $this->model_table)){
                        $this->addCallable('post');
                    }
                }  
            break;    

            case 'PUT':
                if ($this->acl->hasSpecialPermission('write_all', $this->roles)){
                    $this->addCallable('put');
                } else {
                    if ($this->acl->hasResourcePermission('update', $this->roles, $this->model_table)){
                        $this->addCallable('put');
                    }
                }  
            break;

            case 'PATCH':
                if ($this->acl->hasSpecialPermission('write_all', $this->roles)){
                    $this->addCallable('patch');
                } else {
                    if ($this->acl->hasResourcePermission('update', $this->roles, $this->model_table)){
                        $this->addCallable('patch');
                    }
                }  
            break;    

            case 'DELETE':
                if ($this->acl->hasSpecialPermission('write_all', $this->roles)){
                    $this->addCallable('delete');
                } else {
                    if ($this->acl->hasResourcePermission('delete', $this->roles, $this->model_table)){
                        $this->addCallable('delete');
                    }
                }  
            break;
        } 
                    
        
        if ($perms !== NULL)
        {
            // individual permissions *replaces* role permissions
            switch ($_SERVER['REQUEST_METHOD']) {
                /*
                    list_all        64
                    show_all        32 
                    list            16
                    show            8
                    post            4
                    put / patch     2
                    delete          1
                */

                case 'GET': 
                    // sería más eficiente chequear read_all directamente si existe.
                    // usar isAllowed()

                    if ($this->acl->hasResourcePermission('list_all', $this->roles, $this->model_table)){
                        $this->is_listable    = true;
                    } else {
                        $this->is_listable     = (($perms & 16) AND 1) || (($perms & 64) AND 1);
                    }

                    if ($this->acl->hasResourcePermission('show_all', $this->roles, $this->model_table)){
                        $this->is_retrievable    = true;
                    } else {
                        $this->is_retrievable  = (($perms & 8 ) AND 1) || (($perms & 32) AND 1);
                    } 

                    if ($this->is_listable || $this->is_retrievable){
                        $this->addCallable('get');
                    }
                break;
                
                case 'POST': 
                    if (($perms & 4 ) AND 1){
                        $this->addCallable('get');
                    }
                break;    

                case 'PUT':
                    if (($perms & 2 ) AND 1){
                        $this->addCallable('put');
                    }
				break;
                      
                case 'PATCH':
                    if (($perms & 2 ) AND 1){
                        $this->addCallable('putch');
                    }                      
                break;    

                case 'DELETE': 
                    if (($perms & 1 ) AND 1){
                        $this->addCallable('delete');
                    }                    
                break;
            } 
 
        }
        
        $this->impersonated_by = $this->auth->impersonated_by ?? null;

    
        //Debug::dd($this->auth['uid'] ?? NULL, 'uid');
        //Debug::dd($perms, 'permissions');
        //Debug::dd($this->roles, 'roles');    
        //Debug::dd($this->is_listable, 'is_listable?');
        //Debug::dd($this->is_retrievable, 'is_retrievable?');
        //Debug::dd($this->callable, 'callables');
        //Debug::dd($this->impersonated_by, 'impersonated_by);
        //exit;
        
        /*
        if (empty($this->callable)){
            Factory::response()->sendError("Forbidden", 403, "Operation is not permited");
        }
        */
            
        $this->callable = array_merge($this->callable,['head','options']);
        
        //var_export($this->callable);
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
        header('Access-Control-Allow-Credentials: True');
        header('Access-Control-Allow-Headers: Origin,Content-Type,X-Auth-Token,AccountKey,X-requested-with,Authorization,Accept, Client-Security-Token,Host,Date,Cookie,Cookie2'); 
        header('Access-Control-Allow-Methods: GET,POST,PUT,PATCH,POST,DELETE,OPTIONS'); 
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json; charset=UTF-8');

        /*
        $headers = array_merge($this->default_headers, $headers);     

        foreach ($headers as $k => $val){
            if (empty($val))
                continue;
            
            header("${k}:$val");
        }
        */
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
    function options() {
        
    }
 
  
    /**
     * get
     *
     * @param  mixed $id
     *
     * @return void
     */
    function get($id = null) {
        global $api_version;

        $_get   = Factory::request()->getQuery();   
        
        $this->id     = $id;
        $this->folder = Arrays::shift($_get,'folder');

      
        // event hook
        $this->onGettingBeforeCheck($id);

        // Si el rol no le permite a un usuario ver un recurso aunque se le comparta un folder tampoco podrá listarlo
        
        if ($id == null && !$this->is_listable)
            Factory::response()->sendError('Unauthorized', 403, "You are not allowed to list!!!");    

        if ($id != null && !$this->is_retrievable)
            Factory::response()->sendError('Unauthorized', 401, "You are not allowed to retrieve");  

        try {            
            $model    = 'simplerest\\models\\' . $this->model_name;
            $this->instance = (new $model(true))->assoc(); 
                        
            $data    = []; 
            
            // event hook
            $this->onGettingAfterCheck($id);

            $id_name = $this->instance->getIdName();

            if ($id == null) {            
                foreach (['created_by', 'updated_by', 'deleted_by', 'belongs_to', 'user_id'] as $f){
                    if (isset($_get[$f])){
                        if ($_get[$f] == 'me')
                            $_get[$f] = $this->uid;
                        elseif (is_array($_get[$f])){
                            foreach ($_get[$f] as $op => $idx){                            
                                if ($idx == 'me'){
                                    $_get[$f][$op] = $this->uid;
                                }else{      
                                    $p = explode(',',$idx);
                                    if (count($p)>1){
                                    foreach ($p as $ix => $idy){
                                        if ($idy == 'me')
                                            $p[$ix] = $this->uid;
                                        }
                                    }
                                    $_get[$f][$op] = implode(',',$p);
                                }
                            }
                        }else{
                            $p = explode(',',$_get[$f]);
                            if (count($p)>1){
                            foreach ($p as $ix => $idx){
                                if ($idx == 'me')
                                    $p[$ix] = $this->uid;
                                }
                            }
                            $_get[$f] = implode(',',$p);
                        }
                    }
                }
        
                //var_export($_get);
                //exit; ////

                if (isset($_get['created_by']) && $_get['created_by'] == 'me')
                    $_get['created_by'] = $this->uid;

                foreach ($_get as $f => $v){
                    if (!is_array($v) && strpos($v, ',')=== false)
                        $data[$f] = $v;
                } 
            }

            //var_export($_get);
            //exit;
                
            $owned = $this->instance->inSchema(['belongs_to']);

            $_q      = Arrays::shift($_get,'q'); /* search */
            
            
            $fields  = Arrays::shift($_get,'fields');
            $fields  = $fields != NULL ? explode(',',$fields) : NULL;

            $attributes = $this->instance->getAttr();
            
            foreach ((array) $fields as $field){
                if (!in_array($field,$attributes))
                    Factory::response()->sendError("Unknown field '$field'", 400);
            }

            $exclude = Arrays::shift($_get,'exclude');
            $exclude = $exclude != NULL ? explode(',',$exclude) : NULL;

            foreach ((array) $exclude as $field){
                if (!in_array($field,$attributes))
                    Factory::response()->sendError("Unknown field '$field' in exclude", 400);
            }

            $ignored = [];

            if ($exclude != null)
                $this->instance->hide($exclude);
                       
            $pretty  = Arrays::shift($_get,'pretty');

            foreach ($_get as $key => $val){
                if ($val == 'NULL' || $val == 'null'){
                    $_get[$key] = NULL;
                }               
            }

            //var_export($_get);
            //exit;

            if ($this->folder !== null)
            {
                // event hook
                $this->onGettingFolderBeforeCheck($id, $this->folder);  

                $f = DB::table('folders')->assoc();
                $f_rows = $f->where(['id' => $this->folder])->get();
        
                if (count($f_rows) == 0 || $f_rows[0]['tb'] != $this->model_table)
                    Factory::response()->sendError('Folder not found', 404);  
        
                $this->folder_access = $this->acl->hasSpecialPermission('read_all_folders', $this->roles) || $f_rows[0]['belongs_to'] == $this->uid  || $this->hasFolderPermission($this->folder, 'r');   

                if (!$this->folder_access)
                    Factory::response()->sendError("Forbidden", 403, "You don't have permission for the folder $this->folder");
            }

            if ($id != null)
            {
                $_get = [
                    [$id_name, $id]
                ];  

                if (empty($this->folder)){               
                    // root, by id          
                         
                    if ($this->isGuest()){                        
                        if ($this->instance->inSchema(['guest_access'])){
                            $_get[] = ['guest_access', 1];
                        } elseif (!empty(static::$folder_field)) {
                            $_get[] = [static::$folder_field, NULL, 'IS'];
                        } 
                                                
                    } else {
                        if ($owned && !$this->acl->hasSpecialPermission('read_all', $this->roles) && 
                            !$this->acl->hasResourcePermission('show_all', $this->roles, $this->model_table))
                            $_get[] = ['belongs_to', $this->uid];
                    }
                       
                    
                }else{
                    // folder, by id
                    if (empty(static::$folder_field))
                        Factory::response()->sendError("Forbidden", 403, "folder_field is undefined");    
                                           
                    $_get[] = [static::$folder_field, $f_rows[0]['name']];
                    $_get[] = ['belongs_to', $f_rows[0]['belongs_to']];
                }

                //var_export($_get);

                $rows = $this->instance->where($_get)->get($fields); 
                if (empty($rows))
                    Factory::response()->sendError('Not found', 404, $id != null ? "Registry with id=$id in table '{$this->model_table}' was not found" : '');
                else{
                    Factory::response()->send($rows[0]);
                    
                    // event hook
                    $this->onGot($id, $total);
                }
            }else{    
                // "list
                
                $props    = Arrays::shift($_get,'props');
                $group_by = Arrays::shift($_get,'groupBy');
                $having   = Arrays::shift($_get,'having');
             
                $get_limit = function(&$limit){
                    if ($limit == NULL)
                        $limit = min($this->config['paginator']['max_limit'], $this->config['paginator']['default_limit']);
                    else{
                        if ($limit !=0)
                            $limit = min($limit, $this->config['paginator']['max_limit']);
                        else    
                            $limit = $this->config['paginator']['max_limit'];
                    } 
                };
   
                $page  = Arrays::shift($_get,'page');
                $page_size = Arrays::shift($_get,'pageSize');

                if ($page != null)
                    $page = (int) $page;

                if ($page_size  != null)
                    $page_size  = (int) $page_size;

                if ($page != NULL || $page_size != NULL){
                    $get_limit($page_size);

                    if ($page == NULL)
                        $page = 1;

                    $limit  = $page_size;
                    $offset = $page_size * ($page -1);

                    //var_export(['limit' =>$limit, 'offset' => $offset]);
                }else{
                    $limit  = Arrays::shift($_get,'limit');
                    $offset = Arrays::shift($_get,'offset',0);                    

                    $get_limit($limit);
                    $page_size = $limit;
                }

                $order  = Arrays::shift($_get,'orderBy');

                
                // event hook
                $this->onGettingAfterCheck2($id);
                
                //var_export($_get);

                // Importante:
                $_get = Arrays::nonassoc($_get);

                $allops = ['eq', 'gt', 'gteq', 'lteq', 'lt', 'neq'];
                $eqops  = ['=',  '>' , '>=',   '<=',   '<',  '!=' ];

                //var_export($_get);
                //exit;

                foreach ($_get as $key => $val){
                    if (is_array($val)){

                        $campo = $val[0];                       

                        if (is_array($val[1])){                             

                            #var_export($val[1]); ///

                            foreach ($val[1] as $op => $v){

                                #var_export([$op, $v]); ///
                                 
                                switch ($op) {
                                    case 'contains':
                                        $_get[$key] = [$campo, '%'.$v.'%', 'like'];
                                        $ignored[] = $campo;
                                        $data[$campo][] = $v;
                                    break;
                                    case 'notContains':
                                        $_get[$key] = [$campo, '%'.$v.'%', 'not like'];
                                        $ignored[] = $campo;
                                        $data[$campo][] = $v;
                                    break;
                                    case 'startsWith':
                                        $_get[$key] = [$campo, $v.'%', 'like'];
                                        $ignored[] = $campo;
                                        $data[$campo][] = $v;
                                    break;
                                    case 'notStartsWith':
                                        $_get[$key] = [$campo, $v.'%', 'not like'];
                                        $ignored[] = $campo;
                                        $data[$campo][] = $v;
                                    break;
                                    case 'endsWith':
                                        $_get[$key] = [$campo, '%'.$v, 'like'];
                                        $ignored[] = $campo;
                                        $data[$campo][] = $v;
                                    break;
                                    case 'notEndsWith':
                                        $_get[$key] = [$campo, '%'.$v, 'not like'];
                                        $ignored[] = $campo;
                                        $data[$campo][] = $v;
                                    break;
                                    case 'in':                                    
                                        if (strpos($v, ',')!== false){    
                                            $vals = explode(',', $v);
                                            $_get[$key] = [$campo, $vals, 'IN']; 

                                            foreach ($vals as $_v){
                                                $data[$campo][] = $_v;
                                            }
                                        }                                         
                                    break;
                                    case 'notIn':
                                        if (strpos($v, ',')!== false){    
                                            $vals = explode(',', $v);
                                            $_get[$key] = [$campo, $vals, 'NOT IN'];

                                            foreach ($vals as $_v){
                                                $data[$campo][] = $_v;
                                            }
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

                                            $data[$campo][] = $min;
                                            $data[$campo][] = $max;
                                        }                                         
                                    break;
                                    default:
                                        // 'eq', 'gt', ...

                                        $found = false;
                                        foreach ($allops as $ko => $oo){
                                            if ($op == $oo){
                                                $op = $eqops[$ko];
                                                unset($_get[$key]);
                                                $_get[] = [$campo, $v, $op];
                                                $data[$campo][] = $v; // 
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
                                
                                foreach ($vals as $_v){
                                   $data[$campo][] = $_v;
                                }
                            } 
                        }   
                        
                    }                         
                }
                                
                // Si se pide algo que involucra un campo no está en el attr_types lanzar error
                foreach ($_get as $arr){
                    if (!in_array($arr[0],$attributes))
                        Factory::response()->sendError("Unknown field '$arr[0]'", 400);
                }
                

                if (empty($this->folder)){
                    // root, sin especificar folder ni id (lista)   // *             
                    if (!$this->isGuest() && $owned && 
                        !$this->acl->hasSpecialPermission('read_all', $this->roles) &&
                        !$this->acl->hasResourcePermission('list_all', $this->roles, $this->model_table) ){
                        $_get[] = ['belongs_to', $this->uid];     
                    }       
                }else{
                    // folder, sin id

                    if (empty(static::$folder_field)){
                        Factory::response()->sendError("Forbidden", 403, "'folder_field' is undefined");   
                    }    

                    $_get[] = [static::$folder_field, $f_rows[0]['name']];
                    $_get[] = ['belongs_to', $f_rows[0]['belongs_to']];
                }
                
                if ($id == null){
                    $validation = (new Validator())->setRequired(false)->ignoreFields($ignored)->validate($this->instance->getRules(),$data);
                    
                    if ($validation !== true)
                        throw new InvalidValidationException(json_encode($validation));
                }      

                if (!empty($this->folder)) {
                    // event hook
                    $this->onGettingFolderAfterCheck($id, $this->folder);
                }
           
                if (strtolower($pretty) == 'false' || $pretty === 0)
                    $pretty = false;
                else
                    $pretty = true;   

                //var_export($_get); ////
                //var_export($_SERVER["QUERY_STRING"]);

                $query = Factory::request()->getQuery();
                
                if (isset($query['offset'])) 
                    unset($query['offset']);

                if (isset($query['limit'])) 
                    unset($query['limit']);

                if (isset($query['page'])) 
                    unset($query['page']);

                if (!isset($query['pageSize'])) 
                    $query['pageSize'] = $page_size;

                
                //////////
    
                // MIN, MAX, SUM, COUNT, AVG
                if (preg_match('/(min|max|sum|avg|count)\(([a-z\*]+)\)( as [a-z]+)?/i', $props, $matches)){
                    $ag_fn = strtolower($matches[1]);
                    $ag_ff = $matches[2];
                
                    if (preg_match('/[a-z]+\([a-z\*]+\) as ([a-z]+)/i', $props, $matches)){
                        $ag_alias = $matches[1];
                    }else
                        $ag_alias = NULL;
                }

                // WHERE
                $this->instance->where($_get);
                //var_export($_get);

                // GROUP BY
                if ($group_by != NULL){
                    $group_by = explode(',', $group_by);
                    $this->instance->groupBy($group_by);                   
                }                    

                // HAVING
                if (preg_match('/([a-z]+)\(([a-z\*]+)\)([><=]+)([0-9\.]+)/i', $having, $matches)){
                    $hv_fn = strtoupper($matches[1]);
                    $hv_ff = $matches[2];
                    $hv_op = $matches[3];
                    $hv_vv = $matches[4];                   

                    //var_export($matches);                    
                    $this->instance->having(["$hv_fn($hv_ff)", $hv_vv, $hv_op]);
                }elseif (preg_match('/([a-z]+)([><=]+)([0-9\.]+)/i', $having, $matches)){
                    $hv_fn_alias = $matches[1];
                    $hv_op = $matches[2];
                    $hv_vv = $matches[3]; 

                    $this->instance->having([$hv_fn_alias, $hv_vv, $hv_op]);
                }

                // ORDER BY
                if ($order !=  NULL)
                    $this->instance->orderBy($order);
                
                // LIMIT
                if ($limit != NULL)
                    $this->instance->limit($limit);

                // OFFSET
                if ($offset != NULL)
                    $this->instance->offset($offset);

                /*
                    Debe incluir los alias 
                */
                if (!empty($fields))
                    $this->instance->select($fields);


                if (isset($ag_fn)){
                    $rows = $this->instance->$ag_fn($ag_ff, $ag_alias);
                }else                               
                    $rows = $this->instance->get();
                
                    
                //Debug::dd($this->instance->dd2(), 'SQL');
            
                $res = Factory::response()->setPretty($pretty);

                /*
                    Falta paginar cuando hay groupBy & having
                */

                $total = null;

                //  pagino solo sino hay funciones agregativas
                if (!isset($ag_fn)){
                    $total = (int) (new $model(true))->where($_get)->setFetchMode('COLUMN')->count();
                    
                    $page_count = ceil($total / $limit);

                    if ($page == NULL)
                        $page = ceil($offset / $limit) +1;
                    
                    if ($page +1 <= $page_count){
                        $query['page'] = ($page +1);

                        $api_slug = $this->config['REMOVE_API_SLUG'] ? '' : '/api' ;
                        $next =  Url::protocol() . '//' . $_SERVER['HTTP_HOST'] . $api_slug . '/' . $api_version . '/'. $this->model_table . '?' . $query = str_replace(['%5B', '%5D', '%2C'], ['[', ']', ','], http_build_query($query));
                    }else{
                        $next = 'null';
                    }        

                    $pg = [ 
                        'total' => $total,
                        'count' => count($rows),
                        'currentPage' => $page,
                        'totalPages' => $page_count, 
                        'pageSize' => $page_size,
                        'nextUrl' => $next                                              
                    ];  

                    $res->setPaginator($pg);
                }
                               
                // event hooks
                if ($this->folder){
                    $this->onGotFolder($id, $total, $this->folder);
                }

                // event hook
                $this->onGot($id, $total);
                $res->send($rows);
            }

        
        } catch (InvalidValidationException $e) { 
            Factory::response()->sendError('Validation Error', 400, json_decode($e->getMessage()));
        } catch (SqlException $e) { 
            Factory::response()->sendError('SQL Exception', 500, json_decode($e->getMessage())); 
        } catch (\PDOException $e) {    
            Factory::response()->sendError('PDO Exception', 500, $e->getMessage(). ' - '. $this->instance->getLog()); 
        } catch (\Exception $e) {   
            Factory::response()->sendError($e->getMessage());
        }	    
    } // 


    /**
     * post
     *
     * @return void
     */
    function post() {
        $data = Factory::request()->getBody(false);

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $model    = '\\simplerest\\models\\'.$this->model_name;
        $this->instance = (new $model())->assoc();

        $id = $data[$this->instance->getIdName()] ?? null;
        $this->folder = $this->folder = $data['folder'] ?? null;

        try {
            $this->instance->connect();

            // event hook             
            $this->onPostingBeforeCheck($id, $data);
           
            if (!$this->acl->hasSpecialPermission('fill_all', $this->roles)){
                $unfill = [ 
                            'deleted_at',
                            'deleted_by',
                            'updated_at',
                            'updated_by'
                ];    

                if ($this->instance->inSchema(['created_by'])){
                    if (isset($data['created_by'])){
                        Factory::response()->sendError("'created_by' is not fillable", 400);
                    }

                    $data['created_by'] = $this->impersonated_by != null ? $this->impersonated_by : $this->uid;
                }  

            }else{
                $this->instance->fillAll();
            }
    
            if (!$this->acl->hasSpecialPermission('transfer', $this->roles)){    
                if ($this->instance->inSchema(['belongs_to'])){
                    $data['belongs_to'] = $this->uid;
                }
            }   
            
            if ($this->folder !== null)
            {
                if (empty(static::$folder_field))
                    Factory::response()->sendError("Forbidden", 403, "'folder_field' is undefined");

                // event hook    
                $this->onPostingFolderBeforeCheck($id, $data, $this->folder);

                $f = DB::table('folders');
                $f_rows = $f->where(['id' => $this->folder])->get();
                      
                if (count($f_rows) == 0 || $f_rows[0]['tb'] != $this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if ($f_rows[0]['belongs_to'] != $this->uid  && !$this->hasFolderPermission($this->folder, 'w'))
                    Factory::response()->sendError("Forbidden", 403, "You have not permission for the folder $this->folder");

                unset($data['folder']);    
                $data[static::$folder_field] = $f_rows[0]['name'];
                $data['belongs_to'] = $f_rows[0]['belongs_to'];    
            }    

            $validado = (new Validator)->validate($this->instance->getRules(), $data);
            if ($validado !== true){
                Factory::response()->sendError('Data validation error', 400, $validado);
            }  

            if (!empty($this->folder)) {
                // event hook    
                $this->onPostingFolderAfterCheck($id, $data, $this->folder);
            }

            // event hook             
            $this->onPostingAfterCheck($id, $data);

            try {
                $last_inserted_id = $this->instance->create($data);
            } catch (\PDOException $e){
                // solo para debug !
                Factory::response()->sendError("Error: creation of resource fails: ". $e->getMessage(), 500, $this->instance->dd2());
            }

            if ($last_inserted_id !==false){
                // event hooks
                $this->onPostFolder($last_inserted_id, $data, $this->folder);
                $this->onPost($last_inserted_id, $data);

                Factory::response()->send([$this->instance->getKeyName() => $last_inserted_id], 201);
            }	
            else
                Factory::response()->sendError("Error: creation of resource fails!");

        } catch (InvalidValidationException $e) { 
            Factory::response()->sendError('Validation Error', 400, json_decode($e->getMessage()));
        } catch (\Exception $e) {
            Factory::response()->sendError($e->getMessage());
        }	

    } // 
    

    protected function modify($id = NULL, bool $put_mode = false)
    {
        if ($id == null)
            Factory::response()->sendError("Lacks id in request",400);

        $data = Factory::request()->getBody(false);

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        $this->id = $id;    
        $this->folder = $this->folder = $data['folder'] ?? null;
        
        try {
            $model    = 'simplerest\\models\\'.$this->model_name; 
            
            // event hook
            $this->onPuttingBeforeCheck($id, $data);

			if (!$this->acl->hasSpecialPermission('lock', $this->roles)){
                $instance0 = (new $model(true))->assoc();
                $row = $instance0->where([$instance0->getIdName(), $id])->first();

                if (isset($row['locked']) && $row['locked'] == 1)
                    Factory::response()->sendError("Forbidden", 403, "Locked by Admin");
            }

            // Creo una instancia
            $this->instance = (new $model(true))
            ->assoc();
            
            $id_name = $this->instance->getIdName();

            if (!$this->acl->hasSpecialPermission('fill_all', $this->roles)){
                $unfill = [ 
                            'deleted_at',
                            'deleted_by',
                            'created_at',
                            'created_by'
                ];    

                if ($this->instance->inSchema(['updated_by'])){
                    if (isset($data['updated_by'])){
                        Factory::response()->sendError("'updated_by' is not fillable", 400);
                    }

                    $data['updated_by'] = $this->impersonated_by != null ? $this->impersonated_by : $this->uid;
                }  

            }else{
                $this->instance->fillAll();
            }

            $owned = $this->instance->inSchema(['belongs_to']);            

            if ($this->folder !== null)
            {
                if (empty(static::$folder_field))
                    Factory::response()->sendError("'folder_field' is undefined", 403);

                // event hook    
                $this->onPuttingFolderBeforeCheck($id, $data, $this->folder);

                $f = DB::table('folders')->assoc();
                $f_rows = $f->where(['id' => $this->folder])->get();
                      
                if (count($f_rows) == 0 || $f_rows[0]['tb'] != $this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if ($f_rows[0]['belongs_to'] != $this->uid  && !$this->hasFolderPermission($this->folder, 'w') && !$this->acl->hasSpecialPermission('write_all_folders', $this->roles))
                    Factory::response()->sendError("You have not permission for the folder $this->folder", 403);

                $this->folder_name = $f_rows[0]['name'];

                // Creo otra nueva instancia
                $instance2 = (new $model(true))
                ->assoc();

                if (count($instance2->where(['id => $id', static::$folder_field => $this->folder_name])->get()) == 0)
                    Factory::response()->code(404)->sendError("Register for id=$id does not exists");

                unset($data['folder']);    
                $data[static::$folder_field] = $f_rows[0]['name'];
                $data['belongs_to'] = $f_rows[0]['belongs_to'];    
                
            } else {

                $this->instance2 = (new $model(true))
                ->assoc(); 

                // event hook    
                $this->onPuttingBeforeCheck2($id, $data);

                $rows = $this->instance2->where([$id_name => $id])->get();

                if (count($rows) == 0){
                    Factory::response()->code(404)->sendError("Register for id=$id does not exists!");
                }

                if  ($owned && !$this->acl->hasSpecialPermission('write_all', $this->roles) && $rows[0]['belongs_to'] != $this->uid){
                    Factory::response()->sendError('Forbidden', 403, 'You are not the owner');
                }
                    
            }        

            foreach ($data as $k => $v){
                if (strtoupper($v) == 'NULL' && $this->instance->isNullable($k)) 
                    $data[$k] = NULL;
            }
            
            $validado = (new Validator())->setRequired($put_mode)->validate($this->instance->getRules(), $data);
            if ($validado !== true){
                Factory::response()->sendError('Data validation error', 400, $validado);
            }

            if (!empty($this->folder)) {
                // event hook 
                onPuttingFolderAfterCheck($id, $data, $this->folder);
            }

            // event hook
            $this->onPuttingAfterCheck($id, $data);

            try {
                $affected = $this->instance->where([$id_name => $id])->update($data);
                //var_dump($this->instance->dd2());
            } catch (\Exception $e){
                $affected = $this->instance->where([$id_name => $id])->dontExec()->update($data);
                Debug::dd($this->instance->dd2());
            }

            if ($affected !== false) {

                // even hooks        	    
                $this->onPutFolder($id, $data, $affected, $this->folder);
                $this->onPut($id, $data, $affected);
                
                Factory::response()->send("OK");
            } else {
                Factory::response()->sendError("Error in PATCH",404);
            }	

        } catch (InvalidValidationException $e) { 
            Factory::response()->sendError('Validation Error', 400, json_decode($e->getMessage()));
        } catch (\PDOException $e){
            // solo para debug !
            Factory::response()->sendError("Error: creation of resource fails: ". $e->getMessage(), 500);
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
    function put($id = null) {
        $this->modify($id, true);
    } // 
    

    /**
     * patch
     *
     * @param  mixed $id
     *
     * @return void
     */
    function patch($id = NULL) { 
        $this->modify($id);
    } //

        
    /**
     * delete
     *
     * @param  mixed $id
     *
     * @return void
     */
    function delete($id = NULL) {
        if($id == NULL)
            Factory::response()->sendError("Lacks id in request", 400);

        $data = Factory::request()->getBody(false);        

        $this->id = $id;
        $this->folder = $this->folder = $data['folder'] ?? null;

        try {
            $model    = 'simplerest\\models\\'.$this->model_name;
            
            $this->instance = (new $model(true))
            ->assoc()
            ->fill(['deleted_at']); //

            $id_name = $this->instance->getIdName();
            $owned   = $this->instance->inSchema(['belongs_to']);

            $rows  = $this->instance->where([$id_name, $id]);
        
            // event hook
            $this->onDeletingBeforeCheck($id);

            $rows = $this->instance->get();
            //Debug::dd($this->instance->getLastPrecompiledQuery(), 'SQL');
            
            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for $id_name=$id does not exists");
            }

            if ($this->folder !== null)
            {
                if (empty(static::$folder_field))
                    Factory::response()->sendError("'folder_field' is undefined", 403);

                // event hook    
                $this->onDeletingFolderBeforeCheck($id, $this->folder);

                $f = DB::table('folders')->assoc();
                $f_rows = $f->where([$id_name => $this->folder])->get();
                      
                if (count($f_rows) == 0 || $f_rows[0]['tb'] != $this->model_table)
                    Factory::response()->sendError('Folder not found', 404); 
        
                if ($f_rows[0]['belongs_to'] != $this->uid  && !$this->hasFolderPermission($this->folder, 'w'))
                    Factory::response()->sendError("You have not permission for the folder $this->folder", 403);

                $this->folder_name = $f_rows[0]['name'];

                // Creo otra nueva instancia
                $instance2 = (new $model(true))
                ->assoc();

                if (count($instance2->where([$id_name => $id, static::$folder_field => $this->folder_name])->get()) == 0)
                    Factory::response()->code(404)->sendError("Register for $id_name=$id does not exists");

                unset($data['folder']);    
                $data[static::$folder_field] = $f_rows[0]['name'];
                $data['belongs_to'] = $f_rows[0]['belongs_to'];    
            } else {
                if ($owned && !$this->acl->hasSpecialPermission('write_all', $this->roles) && $rows[0]['belongs_to'] != $this->uid){
                    Factory::response()->sendError('Forbidden', 403, 'You are not the owner');
                }
            }  

            $extra = [];

            if ($this->acl->hasSpecialPermission('lock', $this->roles)){
                if ($this->instance->inSchema(['locked'])){
                    $extra = array_merge($extra, ['locked' => 1]);
                }   
            }else {
                if (isset($rows[0]['locked']) && $rows[0]['locked'] == 1){
                    Factory::response()->sendError("Locked by Admin", 403);
                }
            }

            $soft_is_supported  = $this->instance->inSchema(['deleted_by']);

            if ($soft_is_supported){
                $extra = array_merge($extra, ['deleted_by' => $this->impersonated_by != null ? $this->impersonated_by : $this->uid]);
            }               
       
            if (!empty($this->folder)) {
                // event hook    
                $this->onDeletingFolderAfterCheck($id, $this->folder);
            }

            $this->instance->setSoftDelete($soft_is_supported && static::$soft_delete);

            // event hook
            $this->onDeletingAfterCheck($id);

            $affected = $this->instance->delete($extra);
            
            if($affected){
                
                // event hooks
                if ($this->folder !==  null){
                    $this->onDeletedFolder($id, $affected, $this->folder);
                }
                $this->onDeleted($id, $affected);
                
                Factory::response()->sendJson("OK");
            }	
            else
                Factory::response()->sendError("Record not found",404);

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during DELETE for $id_name=$id with message: {$e->getMessage()}");
        }

    } // 


    // It does Not check if 'deleted_at' is in attr_types.
    public static function hasSoftDelete(){
        return static::$soft_delete;
    }


    /*
        API event hooks
    */    

    protected function onGettingBeforeCheck($id) { }
    protected function onGettingAfterCheck($id) { }
    protected function onGettingAfterCheck2($id) { }  ///
    protected function onGot($id, ?int $count){ }

    protected function onDeletingBeforeCheck($id){ }
    protected function onDeletingAfterCheck($id){ }
    protected function onDeleted($id, ?int $affected){ }

    protected function onPostingBeforeCheck($id, Array &$data){ }
    protected function onPuttingBeforeCheck2($id, Array &$data){ }  ///
    protected function onPostingAfterCheck($id, Array &$data){ }
    protected function onPost($id, Array $data){ }

    protected function onPuttingBeforeCheck($id, Array &$data){ }
    protected function onPuttingAfterCheck($id, Array &$data){ }
    protected function onPut($id, Array $data, ?int $affected){ }

     /*
        API event hooks for folder access
    */  

    protected function onGettingFolderBeforeCheck($id, $folder){ } 
    protected function onGettingFolderAfterCheck($id, $folder){ }
    protected function onGotFolder($id, ?int $count, $folder){ }

    protected function onDeletingFolderBeforeCheck($id, $folder){ }
    protected function onDeletingFolderAfterCheck($id, $folder){ }
    protected function onDeletedFolder($id, ?int $affected, $folder){ }

    protected function onPostingFolderBeforeCheck($id, Array &$data, $folder){ }
    protected function onPostingFolderAfterCheck($id, Array &$data, $folder){ }
    protected function onPostFolder($id, Array $data, $folder){ }

    protected function onPuttingFolderBeforeCheck($id, Array &$data, $folder){ }
    protected function onPuttingFolderAfterCheck($id, Array &$data, $folder){ }
    protected function onPutFolder($id, Array $data, ?int $affected, $folder){ }

    
}  