<?php

namespace simplerest\api;

use simplerest\controllers\MyApiController;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Database;

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

            //if (strpos($entity,'?') !== false)
            //    Factory::response()->sendError("Malformed url (? instead of &)", 400); 

            $this->modelName = ucfirst($entity) . 'Model';
            $this->model_table = strtolower($entity);

            $model    = 'simplerest\\models\\'. $this->modelName;
            
            $conn = Database::getConnection();
            $instance = new $model($conn); 
            ////////////////////////////////////////////////////
            
            $fields = Arrays::shift($_get,'fields');
            $fields = $fields != NULL ? explode(',',$fields) : NULL;

            $exclude = Arrays::shift($_get,'exclude');
            $exclude = $exclude != NULL ? explode(',',$exclude) : NULL;

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

                if (!$this->is_admin)
                        $_get[] = ['belongs_to', $this->uid];

                $rows = $instance->filter($fields, $_get); 
                if (empty($rows))
                    Factory::response()->sendError('Not found in trash can', 404);
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
          
                
                // root, sin especificar folder ni id (lista)
                if ($this->is_guest()){
                    if (!$this->guest_root_access)
                        Factory::response()->send([]);

                }else
                    if (!$this->is_admin)
                        $_get[] = ['belongs_to', $this->uid];        
            

                $_get[] = ['deleted_at', NULL, 'IS NOT'];

                //var_dump($_get); ////
                //var_export($_get); 

                if (strtolower($pretty) == 'true' || $pretty == 1)
                    Factory::response()->setPretty(true);
                                  
                $rows = $instance->filter($fields, $_get, null, $order, $limit, $offset);
                Factory::response()->code(200)->send($rows); 
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
    function put(int $id = null){
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
        
        $conn = Database::getConnection();
        $instance = new $model($conn); 
        ////////////////////////////////////////////////////

        ///
        $trashed = $data['trashed'] ?? true;                  
        ///

        $instance->showDeleted(); //
        $instance->id = $id;
        $missing = $instance->diffWithSchema($data, ['id', 'belongs_to']);

        if (!empty($missing))
            Factory::response()->sendError('Lack some properties in your request: '.implode(',',$missing), 400);
       
        try {
            $conn = Database::getConnection();
            $instance->setConn($conn);

            $instance->where(['id', $id]); ///*

            $rows = $instance->filter(null, [
                ['id', $id],
                ['deleted_at', NULL, 'IS NOT']
            ]);

            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists in trash can");
            }

            if (!$this->is_admin)
                    $_get[] = ['belongs_to', $this->uid];
            
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
        
        try {

            /////////////////////////////////////////////////////
            $_get  = Factory::request()->getQuery();

            if (!isset($data['entity']))
                Factory::response()->sendError('Entity is needed in request body', 400);

            $entity = $data['entity']; 
        
            $this->modelName = ucfirst($entity) . 'Model';
            $this->model_table = strtolower($entity);

            $model    = 'simplerest\\models\\'. $this->modelName;
            
            $conn = Database::getConnection();
            $instance = new $model($conn); 
            ////////////////////////////////////////////////////

            ///
            $trashed = $data['trashed'] ?? true;                  
            ///

            $instance->where(['id', $id]);
            $instance->showDeleted(); //

            $rows = $instance->filter(null, [
                ['id', $id],
                ['deleted_at', NULL, 'IS NOT']
            ]);
            
            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists in trash can");
            }

            if (!$this->is_admin)
                $_get[] = ['belongs_to', $this->uid];
            
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

        try { 

            /////////////////////////////////////////////////////
            $_get  = Factory::request()->getQuery();

            $entity = Arrays::shift($_get,'entity'); 
           
            $this->modelName = ucfirst($entity) . 'Model';
            $this->model_table = strtolower($entity);

            $model    = 'simplerest\\models\\'. $this->modelName;
            
            $conn = Database::getConnection();
            $instance = new $model($conn); 
            ////////////////////////////////////////////////////

            $instance->id = $id;

            $instance->showDeleted(); //
            $rows = $instance->filter(null, [
                ['id', $id],
                ['deleted_at', NULL, 'IS NOT']
            ]);
            
            if (count($rows) == 0){
                Factory::response()->code(404)->sendError("Register for id=$id does not exists in trash");
            }
            
            if (!$this->is_admin && $rows[0]['belongs_to'] != $this->uid){
                Factory::response()->sendCode(403);
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
