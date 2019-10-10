<?php

namespace simplerest\libs;

class Arrays 
{
    /**
     * shift
     *
     * @param  array  $arr
     * @param  string $key
     * @param  string $default_value
     *
     * @return mixed
     */
    static function shift(&$arr, $key, $default_value = NULL)
    {
        $out = $arr[$key] ?? $default_value;
        unset($arr[$key]);
        return $out;
    }


    /**
     * nonassoc
     * Associative to non associative array
     * 
     * @param  array $arr
     *
     * @return array
     */
    static function nonassoc(array $arr){
        $out = [];
        foreach ($arr as $key => $val) {
            $out[] = [$key, $val];
        }
        return $out;
    }
 
    function array_key_first(array $arr) {
        foreach($arr as $key => $unused) {
            return $key;
        }
        return NULL;
    }
}

