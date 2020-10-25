<?php

namespace simplerest\libs;

class Time {
    static $unit;   
    static $capture_buffer = false;

    static function setUnit($u){
        static::$unit = $u;
    }

    static function noOutput(){
        ob_start();
        static::$capture_buffer = true;
    }

	static function exec(callable $callback, int $iterations = 100000){
        $start = microtime(true);
    
        for ($i=0; $i<$iterations; $i++){
            call_user_func($callback);	
        }
    
        $t = (microtime(true) - $start) / $iterations;
        
        if (static::$unit == 'MILI'){
            $t = $t * 1000;
        }

        if (static::$unit == 'MICRO'){
           $t = $t * 1000000;
        }

        if (static::$unit == 'NANO'){
            $t = $t * 1000000000;
        }

        if (static::$capture_buffer){
            ob_end_clean(); 
        }

        return $t;
    }

}


