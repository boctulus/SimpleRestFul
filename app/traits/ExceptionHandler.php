<?php

namespace simplerest\traits;

use simplerest\libs\Factory;
use simplerest\libs\DB;
use simplerest\libs\Debug;
use simplerest\libs\Url; 

trait ExceptionHandler
{
    function generateCallTrace()
    {
        $postman = Url::is_postman();
		
		$cli  = (php_sapi_name() == 'cli');
		$br   = ($cli || $postman) ? PHP_EOL : '<br/>';
        $p    = ($cli || $postman) ? PHP_EOL . PHP_EOL : '<p/>';
        $t    = ($cli) ? "\t" : '';

        $e = new \Exception();
        $trace = explode("\n", $e->getTraceAsString());
        // reverse array to make steps line up chronologically
        $trace = array_reverse($trace);
        array_shift($trace); // remove {main}
        array_pop($trace); // remove call to this method
        $length = count($trace);
        $result = array();
       
        for ($i = 0; $i < $length; $i++)
        {
            $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
        }
       
        return $t . implode($br . $t, $result);
    }

    /**
     * exception_handler
     *
     * @param  mixed $e
     *
     * @return void
     */
    function exception_handler($e) {
        DB::closeAllConnections();

        $error_location = $this->config['debug'] ? 'Error on line number '.$e->getLine().' in file - '.$e->getFile() : '';

        $backtrace = $this->generateCallTrace();
        
        if (php_sapi_name() == 'cli'){
            Debug::dd($error_location, 'ERROR LOCATION');
            Debug::dd($e->getMessage(), 'ERR MSG');
            Debug::dd($backtrace, 'BACKTRACE');
        } else {
            if (Factory::config()['debug']){
                Factory::response()->sendError($e->getMessage(), 500, [
                    'location'=> $error_location,
                    'back_trace' => $backtrace
                ]);
            } else {
                Factory::response()->sendError($e->getMessage(), 500);
            }
        }   
    }
    
}
