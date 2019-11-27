<?php

namespace simplerest\api;

use simplerest\controllers\MyApiController;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Database;
use simplerest\libs\Validator;
use simplerest\libs\Url;

class TrashCan extends MyApiController
{   
    function __construct()
    {
        $this->scope['guest'] = [];        
        parent::__construct();
    }

    function get(int $id = null){
        try {            
            /////////////////////////////////////////////////////
            $_get  = Factory::request()->getQuery();

            $entity = Arrays::shift($_get,'entity'); 
            $pretty = Arrays::shift($_get,'pretty');

            if (empty($entity))
                Factory::response()->sendError('Entity is required', 400);

            $this->modelName = ucfirst($entity) . 'Model';
            $this->model_table = strtolower($entity);

            $model    = 'simplerest\\models\\'. $this->modelName;
            
            if (!class_exists($model))
                Factory::response()->sendError("Entity $entity does not exists", 400);

            $conn = Database::getConnection();
            $instance = (new $model($conn))->setFetchMode('ASSOC'); 
            ////////////////////////////////////////////////////
            
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

            foreach ($_get as $key => $val){
                if ($val == 'NULL' || $val == 'null'){
                    $_get[$key] = NULL;
                }               
            } 

            $instance->showDeleted(); //

            if ($id != null)
            {
                $_get = [
                    ['id', $id],
                    ['deleted_at', NULL, 'IS NOT']
                ];  

                if (!$this->is_admin){  
                    $_get[] = ['belongs_to', $this->uid];
                } 

                $rows = $instance->where($_get)->get($fields); 
                if (empty($rows))
                    Factory::response()->sendError('Not found in trash can', 404);
                
                if (!$this->is_admin && isset($rows[0]['locked']) && $rows[0]['locked'] == 1){
                    Factory::response()->sendError("Locked by Admin", 403);
                }
                
                Factory::response()->send($rows[0]);

            }else{    
                // "list

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

                $order  = Arrays::shift($_get,'order');

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
                                    case 'startsWith':
                                        unset($_get[$key]);
                                        $_get[] = [$campo, $v.'%', 'like'];
                                    break;
                                    case 'endsWith':
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
                                    case 'notIn':
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
                        
                    }                           
                }

                // Si se pide algo que involucra un campo no está en el schema lanzar error
                foreach ($_get as $arr){
                    if (!in_array($arr[0],$properties))
                        Factory::response()->sendError("Unknown field '$arr[0]'", 400);
                }
                
                // root, sin especificar folder ni id (lista)
                if ($this->is_guest()){
                    if (!$this->guest_root_access)
                        Factory::response()->send([]);

                }else
                    if (!$this->is_admin)
                        $_get[] = ['belongs_to', $this->uid];        
            

                $_get[] = ['deleted_at', NULL, 'IS NOT'];

        
                if (!$this->is_admin){                  
                    if ($instance->inSchema(['locked'])){
                        $_get[] = ['locked', 1, '!='];
                    }    
                } 

                //var_dump($_get); ////
                //var_export($_get); 

                if (strtolower($pretty) == 'true' || $pretty == 1)
                    Factory::response()->setPretty(true);

                $query = Factory::request()->getQuery();
            
                unset($query['entity']);

                if (isset($query['offset'])) 
                    unset($query['offset']);

                if (isset($query['limit'])) 
                    unset($query['limit']);

                if (isset($query['page'])) 
                    unset($query['page']);

                if (!isset($query['pageSize'])) 
                    $query['pageSize'] = $page_size;

                $count = (new $model($conn))->showDeleted()->where($_get)->count();

                //var_export(['cond' => $_get]);
                //var_export(['count' => $count]);

                $page_count = ceil($count / $limit);

                if ($page == NULL)
                    $page = ceil($offset / $limit) +1;
                
                if ($page +1 <= $page_count){
                    $query['page'] = ($page +1);

                    $next =  Url::protocol() . '//' . $_SERVER['HTTP_HOST'] . '/api/trashCan?entity=' . $this->model_table . '&' . $query = str_replace(['%5B', '%5D'], ['[', ']'], http_build_query($query));
                }else{
                    $next = 'null';
                }

                $pg = ['pages' => $page_count, 'nextUrl' => $next];                
                                  
                $rows = $instance->where($_get)->get($fields, $order, $limit, $offset);
                Factory::response()->code(200)->setPaginator($pg)->send($rows); 
            }

        } catch (\Exception $e) {
            Factory::response()->sendError($e->getMessage());
        }	    
    } // 

    function post(){
        Factory::response()->sendError('You can not create a trashcan resource',405);
    }        

    /**
     * put
     *
     * @param  int $id
     *
     * @return void
     */
    protected function modify($id = NULL, bool $put_mode = false)
    {
        if ($id == null)
            Factory::response()->code(400)->sendError("Lacks id in request");

        $data = Factory::request()->getBody();

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        /////////////////////////////////////////////////////
        $_get  = Factory::request()->getQuery();

        if (!isset($data['entity']))
            Factory::response()->sendError('Entity is needed in request body', 400);

        $entity = $data['entity']; 
       
        $this->modelName = ucfirst($entity) . 'Model';
        $this->model_table = strtolower($entity);

        $model    = 'simplerest\\models\\'. $this->modelName;

        if (!class_exists($model))
            Factory::response()->sendError("Entity $entity does not exists", 400);
        
        $conn = Database::getConnection();
        $instance = (new $model($conn))->setFetchMode('ASSOC'); 
        ////////////////////////////////////////////////////

        ///
        $trashed = $data['trashed'] ?? true;                  
        ///

        $instance->showDeleted(); //
        $instance->fill(['deleted_at']);
        //$missing = $instance->diffWithSchema($data, ['id', 'belongs_to']);

        //if (!empty($missing))
        //    Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing), 400);
       
        try {
            $conn = Database::getConnection();
            $instance->setConn($conn);

            $rows = $instance->where([
                ['id', $id],
                ['deleted_at', NULL, 'IS NOT']
            ])->get();

            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists in trash can");
            }
         

            if (!$this->is_admin){
                $_get[] = ['belongs_to', $this->uid];

                if (isset($rows[0]['locked']) && $rows[0]['locked'] == 1){
                    Factory::response()->sendError("Locked by Admin", 403);
                }
            }
                
            
            if (!$this->is_admin && $rows[0]['belongs_to'] != $this->uid){
                Factory::response()->sendCode(403);
            }                
            
            foreach ($data as $k => $v){
                if (strtoupper($v) == 'NULL' && $instance->isNullable($k)) 
                    $data[$k] = NULL;
            }

            //////////////////////////////////
            if (isset($data['trashed']))
                unset($data['trashed']);

            unset($data['entity']);    

            if (strtolower($trashed) === "false" || $trashed === 0){
                $data['deleted_at'] = NULL;
            }
            //////////////////////////////////

            $validado = (new Validator())->setRequired($put_mode)->validate($instance->getRules(), $data);
            if ($validado !== true){
                Factory::response()->sendError(_('Data validation error'), 400, $validado);
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

        try { 

            /////////////////////////////////////////////////////
            $_get  = Factory::request()->getQuery();

            if (!isset($data['entity']))
                Factory::response()->sendError('Entity is needed in request body', 400);

            $entity = $data['entity'];    
           
            $this->modelName = ucfirst($entity) . 'Model';
            $this->model_table = strtolower($entity);

            $model    = 'simplerest\\models\\'. $this->modelName;

            if (!class_exists($model))
                Factory::response()->sendError("Entity $entity does not exists", 400);
            
            $conn = Database::getConnection();
            $instance = (new $model($conn))->setFetchMode('ASSOC'); 
            ////////////////////////////////////////////////////

            $instance->fill(['deleted_at']); //

            $instance->showDeleted(); //
            $rows = $instance->where([
                ['id', $id],
                ['deleted_at', NULL, 'IS NOT']
            ])->get();

            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists in trash");
            }
            
            if (!$this->is_admin && $rows[0]['belongs_to'] != $this->uid){
                Factory::response()->sendCode(403);
            }         
                        
            if (isset($rows[0]['locked']) && $rows[0]['locked'] == 1){
                Factory::response()->sendError("Locked by Admin", 403);
            }

            if($instance->delete(false)){
                Factory::response()->sendJson("OK");
            }	
            else
                Factory::response()->sendError("Record not found in trash can",404);

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during PATCH for id=$id with message: {$e->getMessage()}");
        }

    } // 

        
} // end class
