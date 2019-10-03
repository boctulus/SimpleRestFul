<?php

namespace simplerest\libs;

class Arrays 
{
    static function shift(&$arr, $key, $default_value = NULL)
    {
        $out = $arr[$key] ?? $default_value;
        unset($arr[$key]);
        return $out;
    }

    /*
        Associative to non associative array
    */
    static function nonassoc($arr){
        $out = [];
        foreach ($arr as $key => $val) {
            $out[] = [$key, $val];
        }
        return $out;
    }
    
}

