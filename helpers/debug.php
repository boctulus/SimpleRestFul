<?php
//Namespace Debug;

if (!function_exists('debug'))
{
	function debug($v,$msg=null,$exit=false) 
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
			print '<pre>';
			print_r($v);
			print '</pre>';
		}
	
		print("\n");
			
		if ($exit)		
			die("\nEND.");		
		
			
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


