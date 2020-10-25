<?php

namespace simplerest\traits;

trait Uuids
{
    protected function boot()
    {
        parent::boot();
        
        $this->registerInputMutator($this->getIdName(), function($id){ 
			return uuid_create(UUID_TYPE_RANDOM); 
		}, function($op, $dato){
            return ($op == 'CREATE');
		});  
    }    
}
