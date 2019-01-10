<?php

class Request 
{
    static protected $query;
    static protected $raw;

    static function setQuery($query){
        self::$query = $query;
    }

    static function getHeaders(){
        return apache_request_headers();
    }

    // getter destructivo
    static public function shift($key, $default_value = NULL)
    {
        $out = self::$query[$key] ?? $default_value;
        unset(self::$query[$key]);
        return $out;
    }

    static public function getQuery()
    {
        return self::$query;
    }

    static public function setRaw($raw)
    {
        self::$raw = $raw;
    }

    static public function getRaw()
    {
        return self::$raw;
    }

    static public function getJson($assoc = true)
    {
        return json_decode(self::$raw, $assoc);
    }

}