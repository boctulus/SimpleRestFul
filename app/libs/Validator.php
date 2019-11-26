<?php

namespace simplerest\libs;

use simplerest\core\interfaces\IValidator;

/*
	Validador de campos de formulario
	Ver 2.0 Beta

	@author boctulus
	
	Novedad: el desacople de reglas y datos
*/
class Validator implements IValidator
{
	protected $required  = true;
	protected $as_string = false;
	protected $ignored_fields = [];

	function setRequired(bool $state){
		$this->required = $state;
		return $this;
	}

	function ignoreFields(array $fields){
		$this->ignored_fields = $fields;
		return $this;
	}

	function asString(){
		$this->as_string = true; 
		return $this;
	}

	/*
		@param string $dato
		@param string $tipo
		@return mixed
		
		El tipo de dato es obligatorio (no nulo)
		
		Los	tipos admitidos son	'int' | 'decimal' | 'numeric' | 'string' | 'notnum' |
		'email' | 'date' | 'time' | 'regex:/expresion/' y sus alias.
		
		Se ha considerado innecesario un tipo boolean
	*/
	static function isType($dato, $tipo){
		if ($dato === NULL)
			throw new \InvalidArgumentException('Data can not be null'); 
		
		if (empty($tipo))
			throw new \InvalidArgumentException('Data type is undefined');

		if ($tipo == 'int' || $tipo == 'integer'){
			return preg_match('/^(-?[0-9]+)$/',trim($dato)) == 1;
		}elseif($tipo == 'decimal' || $tipo == 'float' || $tipo == 'double'){
			$dato = trim($dato);
			return is_numeric($dato);
		}elseif($tipo == 'number'){
			$dato = trim($dato);
			return ctype_digit($dato) || is_numeric($dato);
		}elseif($tipo == 'string' || $tipo == 'str'){
			return is_string($dato);
		}elseif($tipo == 'alpha'){                                     
			return (preg_match('/^[\pL\pM\p{Zs}.-]+$/u',$dato) == 1); 		
		}elseif($tipo == 'notnum'){
			return preg_match('/[0-9]+/',$dato) == 0;
		}elseif($tipo == 'email'){
				return filter_var($dato, FILTER_VALIDATE_EMAIL);
		}elseif($tipo == 'date'){
				return get_class()::isValidDate($dato);
		}elseif($tipo == 'datetime'){
				return preg_match('/[1-2][0-9]{3}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-5][0-9]/',$dato)== 1;		
		}elseif($tipo == 'time'){
				return get_class()::isValidDate($dato,'H:i:s');	
		// formato: 'regex:/expresion/'			
		}elseif ((substr($tipo,0,7)=='regex:/') /* && (substr($tipo,-1)=='/') */ ){
			try{
				$regex = substr($tipo,6);
				return preg_match($regex,$dato) == 1;	
			}catch(\Exception $e){
				throw new \InvalidArgumentException('Invalid regular expression!');
			}	
		}elseif($tipo == 'array'){
				return is_array($dato);			
		}else
			throw new \InvalidArgumentException(sprintf(_('Invalid data type for %s'), $dato));	
	}	

	/*
		@param array $rules
		@param array $data
		@return mixed

	*/
	function validate(array $rules, array $data){

		//Debug::debug($data, 'DATA:');

		if (empty($rules))
			throw new \InvalidArgumentException('No validation rules!');
		
		//if (empty($data))
		//	throw new \InvalidArgumentException('No data!');
	
		$errores = [];
		
		/*
			Crea array con el campo como índice
		*/
		$push_error = function ($campo, array $error, array &$errores){
			if(isset($errores[$campo]))
				$errores[$campo][] = $error;
			else{
				$errores[$campo] = [];
				$errores[$campo][] = $error;
			}	
		};
			
		foreach($rules as $field => $rule){

			//Debug::debug($rule, "RULE $field :");
			
			if (!isset($data[$field]))
				continue;
			
			//Debug::debug($data[$field], 'VALOR:');			
			//echo "\n";
			
			if (!isset($data[$field])){
				if ($this->required && isset($rule['required']) && $rule['required']){
					$push_error($field,['data'=> null, 'error'=> 'required', 'error_detail' =>sprintf(_('%s is required', $field))],$errores);
				}

				continue;
			}
				
			
			$dato = $data[$field];
			
			if(in_array($field, (array) $this->ignored_fields))
				continue;
			
			if (!isset($rule['required']) || $this->required)
				$rule['required'] = false;

			$avoid_type_check = false;
			if($rule['required']){
				if(trim($dato)=='')
					$push_error($field,['data'=>$dato, 'error'=>'required', 'error_detail' => sprintf(_('%s is required'), $field)],$errores);
			}	
			
			if (isset($rule['type']) && in_array($rule['type'],['numeric','number','int','integer','float','double','decimal']) && trim($dato)=='')
				$avoid_type_check = true;
			
			if (isset($rule['type']) && !$avoid_type_check)
				if (!get_class()::isType($dato, $rule['type']))
					$push_error($field,['data'=>$dato, 'error'=>'type', 'error_detail' => _("It's not a valid {$rule['type']}")],$errores);
				
					
			if(isset($rule['type'])){	
				if(in_array($rule['type'],['str','string','notnum','email'])){
						
						if(isset($rule['min'])){ 
							$rule['min'] = (int) $rule['min'];
							if(strlen($dato)<$rule['min'])
								$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => sprintf(_('Minimum length is %d'),$rule['min'])],$errores);
						}
						
						if(isset($rule['max'])){ 
							$rule['max'] = (int) $rule['max'];
							if(strlen($dato)>$rule['max'])
								$push_error($field,['data'=>$dato, 'error'=>'max', 'error_detail' => sprintf(_('Maximum length is %d'), $rule['max'])],$errores);
						}
				}	
				
				if(in_array($rule['type'],['numeric','number','int','integer','float','double','decimal'])){
						
						if(isset($rule['min'])){ 
							$rule['min'] = (int) $rule['min'];
							if($dato<$rule['min'])
								$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => sprintf(_('Minimum is %d'), $rule['min'])],$errores);
						}
						
						if(isset($rule['max'])){ 
							$rule['max'] = (int) $rule['max'];
							if($dato>$rule['max'])
								$push_error($field,['data'=>$dato, 'error'=>'max', 'error_detail' => sprintf(_('Maximum is %d'), $rule['max'])],$errores);
						}
				}	
				
				if(in_array($rule['type'],['time','date'])){
						
					$t0 = strtotime($dato);

					/*
					if (!$t0){
						$push_error($field,['data'=>$dato, 'error'=>'type', 'error_detail' => 'Invalid date'],$errores);
					}
					*/

					if(isset($rule['min'])){ 
						if($t0<strtotime($rule['min']))
							$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => 'Minimum is '.$rule['min']],$errores);
					}
					
					if(isset($rule['max'])){ 
						if($t0>strtotime($rule['max']))
							$push_error($field,['data'=>$dato, 'error'=>'max', 'error_detail' => 'Maximum is '.$rule['max']],$errores);
					}
				}	
				
				if($rule['type']=='array' && is_array($dato)){
						$rule['min'] = (int) $rule['min'];
						if(isset($rule['min'])){ 
							if(count($dato)<$rule['min'])
								$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => 'Minimum is '.$rule['min'].' opciones'],$errores);
						}
						
						if(isset($rule['max'])){ 
						$rule['max'] = (int) $rule['max'];
							if(count($dato)>$rule['max'])
								$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => 'Maximum is '.$rule['max'].' opciones'],$errores);
						}
				}
				
			}	
				
		}

		$validated = empty($errores) ? true : $errores;

		if ($this->as_string){
			if ($validated !== true){
				
				$e = [];
				foreach ($validated as $field => $errors){
					$fe = [];
					foreach ($errors as $error){
						$fe[] = $error['error_detail'];
					}
					$e[] = "$field => ". implode(' & ', $fe);
				}	
	
				return implode ('; ', $e);
			}  
		}

		return $validated;
	}
	
	
	
	private static function isValidDate($date, $format = 'd-m-Y') {
		$dateObj = \DateTime::createFromFormat($format, $date);
		return $dateObj && $dateObj->format($format) == $date;
	}
}


/*
	Helper
*/


// @author: vitalyart dot ru
if (!function_exists('array_key_first')) {
    /**
     * Gets the first key of an array
     *
     * @param array $array
     * @return mixed
     */
    function array_key_first(array $array)
    {
        if (count($array)) {
            reset($array);
            return key($array);
        }

        return null;
    }
}

