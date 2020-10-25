<?php

namespace simplerest\core\api\v1;

use simplerest\controllers\MyApiController;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Strings;
use simplerest\libs\DB;
use simplerest\libs\Debug;
use simplerest\libs\Validator;
use simplerest\core\exceptions\InvalidValidationException;
use simplerest\libs\Url;


class Collections extends MyApiController
{   
    protected $forbidden_tables = [
        'folder_other_permissions',
        'folder_permissions',
        'folders',
        'users',
        'roles',
        'user_roles',
        'sp_permissions',
        'user_sp_permissions',
        'user_tb_permissions',
    ];

    function __construct()
    {
        if (Factory::request()->hasAuth()){
            $this->callable = ['get', 'post', 'put', 'patch', 'delete'];

            $this->is_listable = true;
            $this->is_retrievable = true;
        }  
        parent::__construct();
    }

    function post() {
        $data = Factory::request()->getBody(false);  

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);
        
        if (empty($data['entity']))
            Factory::response()->sendError('entity is required', 400);    

        if (empty($data['refs']))
            Factory::response()->sendError('refs is required', 400);        

        $entity = $data['entity'];
        $refs   = $data['refs'];

        if (in_array(strtolower($entity), $this->forbidden_tables)){
            Factory::response()->sendError('Forbidden', 403, "Table '". $entity . "' is not available for collections");
        }

        try {            
            $entity = Strings::toCamelCase($entity);    
           
            $model_name   = ucfirst($entity) . 'Model';
            $table_name = strtolower($entity);

            $model    = 'simplerest\\models\\'. $model_name;
            $api_ctrl = '\simplerest\\controllers\\api\\' . ucfirst($entity);

            if (!class_exists($model))
                Factory::response()->sendError("Entity $entity does not exists", 400);
            
                      
            $id = DB::table('collections')->create([
                'entity' => $table_name,
                'refs' => json_encode($refs),
                'belongs_to' => $this->uid
            ]);

            if ($id !== false){
                Factory::response()->send(['id' => $id], 201);
            }	
            else
                Factory::response()->sendError("Error: creation of collection fails!");

        } catch (\Exception $e) {
            Factory::response()->sendError($e->getMessage());
        }

    } // 
    
    protected function modify($id = NULL, bool $put_mode = false)
    {
        if ($id == null)
            Factory::response()->code(400)->sendError("Lacks id in request");

        $data = Factory::request()->getBody(false);

        if (empty($data))
            Factory::response()->sendError('Invalid JSON',400);

        try {
            
            if (!empty($data['remove']) && strtolower($data['remove']) == 'true'){
                if (DB::table('collections')->where(['id', $id])->delete()){
                    Factory::response()->sendOK();
                } else {
                    Factory::response()->sendError("Colection not found",404);
                }
            } else {

                $row  = DB::table('collections')->where(['id', $id])->first();
            
                if (!$row){
                    Factory::response()->code(404)->sendError("Collection for id=$id does not exists");
                }
            
                $sp = Factory::acl()->hasSpecialPermission('write_all_collections', $this->roles);

                if (!$sp && $row['belongs_to'] != $this->uid){
                    Factory::response()->sendError('Forbidden', 403, 'You are not the owner');
                }         
                               
                $entity = Strings::toCamelCase($row['entity']);    
           
                $model_name   = ucfirst($entity) . 'Model';
                $table_name = strtolower($entity);

                $model    = 'simplerest\\models\\'. $model_name;
                $api_ctrl = '\simplerest\\controllers\\api\\' . ucfirst($entity);

                if (!class_exists($model))
                    Factory::response()->sendError("Entity $entity does not exists", 400);     
                
                $instance = (new $model(true));      

                unset($data['refs']);

                foreach ($data as $k => $v){
                    if (strtoupper($v) == 'NULL' && $instance->isNullable($k)) 
                        $data[$k] = NULL;
                }

                // Table must have 'belongs_to' 
                if (!$sp) {
                    $instance->where(['belongs_to' => $this->uid]);
                }

                $validado = (new Validator())->setRequired($put_mode)->validate($instance->getRules(), $data);
                if ($validado !== true){
                    Factory::response()->sendError('Data validation error', 400, $validado);
                }   
                
                if ($instance->inSchema(['updated_by'])){
                    $data['updated_by'] = $this->uid;
                }                

                $refs = json_decode($row['refs']);

                $affected = 0;
                DB::transaction(function() use ($instance, $refs, &$affected, $data, $id) {
                    $affected = $instance->whereIn('id', $refs)
                    ->update($data);
                    
                    DB::table('collections')->where(['id' => $id])->delete();
                });     

                Factory::response()->send(['affected_rows' => $affected]); 
            }

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during update for colection $id with message: {$e->getMessage()}");
        }
    }        


     /**
     * delete
     *
     * @param  mixed $id
     *
     * @return void
     */
    function delete($id = NULL) {
        if($id == NULL)
            Factory::response()->sendError("Lacks id for Collection in request", 400);

        $data = Factory::request()->getBody();  

        try {            

            $row  = DB::table('collections')->where(['id', $id])->first();
            
            if (!$row){
                Factory::response()->code(404)->sendError("Collection for id=$id does not exists");
            }
            
            $sp = Factory::acl()->hasSpecialPermission('write_all_collections', $this->roles);

            if (!$sp && $row['belongs_to'] != $this->uid){
                Factory::response()->sendError('Forbidden', 403, 'You are not the owner');
            }         

            $entity = Strings::toCamelCase($row['entity']);    
           
            $model_name = ucfirst($entity) . 'Model';
            $table_name = strtolower($entity);

            $model    = 'simplerest\\models\\'. $model_name;
            $api_ctrl = '\simplerest\\controllers\\api\\' . ucfirst($entity);

            if (!class_exists($model))
                Factory::response()->sendError("Entity $entity does not exists", 400);
                 
            $instance = (new $model(true));     

            // Table must have 'belongs_to' 
            if (!$sp) {
                $instance->where(['belongs_to' => $this->uid]);
            }    
            
            $refs = json_decode($row['refs']);
            $affected = 0;
            DB::transaction(function() use ($instance, $api_ctrl, $refs, $id, &$affected) {
                $affected = $instance->whereIn('id', $refs)
                ->delete();

                DB::table('collections')->where(['id' => $id])->delete();
            });   

            //echo " affected ( $affected )";

            Factory::response()->send(['affected_rows' => $affected]);  

        } catch (\Exception $e) {
            Factory::response()->sendError("Error during DELETE for collection $id with message: {$e->getMessage()}");
        }

    } // 
        
        
} // end class
