<?php
//Namespace Debug;

if (!function_exists('dd'))
{
	function dd($var, $prettify = false, $exit = true){
		if ($prettify){
			print '<pre>';
			print_r($var);
			print '</pre>';
		}
		else{
			var_dump($var);
			if ($exit)
				exit;
		}
	}
}

if (!function_exists('debug'))
{
	function debug($v, $msg=null, $exit=false, $prettify = true) 
	{			
		if (gettype($v)=='boolean'){
			echo ($v ? "TRUE" : "FALSE");
		}	
	
		if (php_sapi_name() == 'cli')
		{
			if ($msg!="")
				echo $msg."\n";

			print_r($v);	
		}else{	
			if ($msg!="")
				echo $msg."<br/>";
			
			if ($prettify){
				print '<pre>';
				print_r($v);
				print '</pre>';
			}else
				print_r($v);	
		}
		
		if ($exit)		
			exit;				
	}		
}	

// devuelve un var_dump() como json 
function json_var_dump($ar){		
	return json_encode(var_export($ar));			
}	

function json_var_dump_v2($var){
   ob_start();
   var_dump($var);
   return json_encode(ob_get_clean());            
}     

/*
	@author mario
	http://stackoverflow.com/questions/24316347/how-to-format-var-export-to-php5-4-array-syntax
*/
function var_export2($var, $indent="") {
    switch (gettype($var)) {
        case "string":
            return '"' . addcslashes($var, "\\\$\"\r\n\t\v\f") . '"';
        case "array":
            $indexed = array_keys($var) === range(0, count($var) - 1);
            $r = [];
            foreach ($var as $key => $value) {
                $r[] = "$indent    "
                     . ($indexed ? "" : var_export2($key) . " => ")
                     . var_export54($value, "$indent    ");
            }
            return "[\n" . implode(",\n", $r) . "\n" . $indent . "]";
        case "boolean":
            return $var ? "TRUE" : "FALSE";
        default:
            return var_export($var, TRUE);
    }
}

/** 
*	@author https://stackoverflow.com/users/1709587
*/
function isAssoc(array $arr)
{
    if (array() === $arr) return false;
    return array_keys($arr) !== range(0, count($arr) - 1);
}

/**
 * Replaces any parameter placeholders in a query with the value of that
 * parameter. Useful for debugging. Assumes anonymous parameters from 
 * $params are are in the same order as specified in $query
 *
 * @param string $query The sql query with parameter placeholders
 * @param array $params The array of substitution parameters
 * @return string The interpolated query
 * 
 * @author maerlyn https://stackoverflow.com/users/308825/maerlyn
 */
function interpolateQuery($query, $params) {
    $keys = array();

	# build a regular expression for each parameter

	if (!isAssoc($params)){
		$_params = [];  // associative array
		foreach($params as $param){
			$key = $param[0];
			$value = $param[1];
			// $type = $param[2];
			$_params[$key] = $value;
		}
		$params  = $_params;
	}		

	foreach ($params as $key => $value) {
		if (is_string($key)) {
			$keys[] = '/'.((substr($key,0,1)==':') ? '' : ':').$key.'/';
		} else {
			$keys[] = '/[?]/';
		}
	}
	
    $query = preg_replace($keys, $params, $query, 1, $count);

    #trigger_error('replaced '.$count.' keys');

	return $query;
}
