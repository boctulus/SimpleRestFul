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
	protected $ignored_fields = [];

	function setRequired(bool $state){
		$this->required = $state;
		return $this;
	}

	function ignoreFields(array $fields){
		$this->ignored_fields = $fields;
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
			throw new \InvalidArgumentException('No data'); 
		
		if (empty($tipo))
			throw new \InvalidArgumentException('Data type is undefined');

		if ($tipo == 'bool'){
			return $dato == 0 || $dato == 1;
		}elseif ($tipo == 'int' || $tipo == 'integer'){
			return preg_match('/^(-?[0-9]+)+$/',trim($dato)) == 1;
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
		}elseif($tipo == 'sql_datetime'){
				return preg_match('/[1-2][0-9]{3}-[0-9]{2}-[0-9]{2} [0-9]{2}:[0-9]{2}:[0-5][0-9]/',$dato)== 1;		
		}elseif($tipo == 'time'){
				return get_class()::isValidDate($dato,'H:i:s');	
		// formato: 'regex:/expresion/'			
		}elseif ((substr($tipo,0,6)=='regex:')){
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
		//var_export(['rules' => $rules, 'data' => $data]);

		// i18n
        bindtextdomain('validator', LOCALE_PATH);
		textdomain('validator');

		//Debug::dd($data, 'DATA:');

		if (empty($rules))
			throw new \InvalidArgumentException('No validation rules!');
		
		// no habilitar:
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
			
		$msg = [];
		foreach($rules as $field => $rule){
			//Debug::dd($rule, "RULE $field :");
			
			//var_export(array_diff(array_keys($rule), ['messages']));
			if (isset($rules[$field]['messages'])){
				$msg[$field] = $rule['messages'];
			}				

			//if (isset($data[$field]))			
			//	Debug::dd($data[$field], 'VALOR:');			

			//echo "---------------------------------<p/>\n";
			

			// multiple-values for each field

			if (!isset($data[$field])){
				if ($this->required && isset($rule['required']) && $rule['required']){
					$err = (isset($msg[$field]['required'])) ? $msg[$field]['required'] :  "%s is required";
					$push_error($field,['data'=> null, 'error'=> 'required', 'error_detail' =>sprintf(_($err), $field)],$errores);
				}	
				
				continue;
			}
				
			foreach ((array) $data[$field] as $dato){
				//Debug::dd(['field' => $field, 'data' => $dato]);

				//var_export($rules[$field]['messages']);
				//var_export(array_diff(array_keys($rule), ['messages']));

				//$constraints = array_diff(array_keys($rule), ['messages']);
				//var_export($constraints);

				if (!isset($dato) || $dato == '' || $dato == null){
					//var_export(['field' =>$dato, 'required' => $rule['required']]);
	
					if ($this->required && isset($rule['required']) && $rule['required']){
						$err = (isset($msg[$field]['required'])) ? $msg[$field]['required'] :  "%s is required";
						$push_error($field,['data'=> null, 'error'=> 'required', 'error_detail' =>sprintf(_($err), $field)],$errores);
					}
	
					continue 2;
				}
				
				if(in_array($field, (array) $this->ignored_fields))
					continue 2;
				
				if (!isset($rule['required']) || $this->required)
					$rule['required'] = false;
	
				$avoid_type_check = false;
				if($rule['required']){
					if(trim($dato)==''){
						$err = (isset($msg[$field]['required'])) ? $msg[$field]['required'] :  "%s is required";
						$push_error($field,['data'=>$dato, 'error'=>'required', 'error_detail' => sprintf(_($err), $field)],$errores);
					}						
				}	
				
				if (isset($rule['in'])){
					$err = (isset($msg[$field]['in'])) ? $msg[$field]['in'] :  "$dato is not one of the allowed values";
					if (!in_array($dato, $rule['in'])){
						$push_error($field,['data'=>$dato, 'error'=>'in', 'error_detail' => sprintf(_($err), $field)],$errores);
					}					
				}

				if (isset($rule['between'])){
					if ($dato > $rule['between'][1] || $dato < $rule['between'][0]){
						$err = (isset($msg[$field]['between'])) ? $msg[$field]['between'] :  "$dato is not between {$rule['between'][0]} and {$rule['between'][1]}";
						$push_error($field,['data'=>$dato, 'error'=>'between', 'error_detail' => sprintf(_($err), $field)],$errores);
					}					
				}

				if (isset($rule['type']) && in_array($rule['type'],['numeric','number','int','integer','float','double','decimal']) && trim($dato)=='')
					$avoid_type_check = true;
				
				if (isset($rule['type']) && !$avoid_type_check)
					if (!get_class()::isType($dato, $rule['type'])){
						$err =  (isset($msg[$field]['type'])) ? $msg[$field]['type'] : "It's not a valid {$rule['type']}";
						$push_error($field,['data'=>$dato, 'error'=>'type', 'error_detail' => _($err)],$errores);
					}						
					
						
				if(isset($rule['type'])){	
					if (in_array($rule['type'],['str','string','notnum','email']) || strpos($rule['type'], 'regex:') === 0 ){
							
							if(isset($rule['min'])){ 
								$rule['min'] = (int) $rule['min'];
								if(strlen($dato)<$rule['min']){
									$err = (isset($msg[$field]['min'])) ? $msg[$field]['min'] :  "Minimum length is %d";
									$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => sprintf(_($err),$rule['min'])],$errores);
								}									
							}
							
							if(isset($rule['max'])){ 
								$rule['max'] = (int) $rule['max'];
								if(strlen($dato)>$rule['max']){
									$err = (isset($msg[$field]['max'])) ? $msg[$field]['max'] :  'Maximum length is %d';
									$push_error($field,['data'=>$dato, 'error'=>'max', 'error_detail' => sprintf(_($err), $rule['max'])],$errores);
								}
									
							}
					}	
					
					if(in_array($rule['type'],['numeric','number','int','integer','float','double','decimal'])){
							
							if(isset($rule['min'])){ 
								$rule['min'] = (int) $rule['min'];
								if($dato<$rule['min']){
									$err = (isset($msg[$field]['min'])) ? $msg[$field]['min'] :  'Minimum is %d';
									$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => sprintf(_($err), $rule['min'])],$errores);
								}
									
							}
							
							if(isset($rule['max'])){ 
								$rule['max'] = (int) $rule['max'];
								if($dato>$rule['max']){
									$err = (isset($msg[$field]['max'])) ? $msg[$field]['max'] :  'Maximum is %d';
									$push_error($field,['data'=>$dato, 'error'=>'max', 'error_detail' => sprintf(_($msg), $rule['max'])],$errores);
								}
									
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
							if($t0<strtotime($rule['min'])){
								$err = (isset($msg[$field]['min'])) ? $msg[$field]['min'] :  'Minimum is '.$rule['min'];
								$push_error($field,['data'=>$dato, 'error'=>'min', 'error_detail' => _($msg)],$errores);
							}
								
						}
						
						if(isset($rule['max'])){ 
							if($t0>strtotime($rule['max'])){
								$err = (isset($msg[$field]['max'])) ? $msg[$field]['max'] : 'Maximum is '.$rule['max'];
								$push_error($field,['data'=>$dato, 'error'=>'max', 'error_detail' => _($msg)],$errores);
							}
								
						}
					}	
					
				}	
			}
							
		}

		$validated = empty($errores) ? true : $errores;

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

