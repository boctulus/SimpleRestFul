<?php

include 'interfaces/arrayable.php';

class Request  implements Arrayable, ArrayAccess
{
    static protected $query_arr;
    static protected $raw;
    static protected $params;
    static protected $instance = NULL;

    protected function __construct() { }

    static function getInstance(){
        if(static::$instance == NULL){
            parse_str($_SERVER['QUERY_STRING'], static::$query_arr);
            static::$raw = file_get_contents("php://input");
            static::$instance = new static();
            return static::$instance;
           // dd('Acabo de crear una nueva instancia');
        }else{
            //dd('Por retornar instancia existente...');
            return static::$instance;
        }
    }

    static function setParams($params){
        static::$params = $params;
    }

    function headers(){
        return apache_request_headers();
    }

    public function getQuery($key = null)
    {
        if ($key == null)
            return static::$query_arr;
        else 
             return static::$query_arr[$key];   
    }

    // getter destructivo sobre $query_arr
    public function shift($key, $default_value = NULL)
    {
        $out = static::$query_arr[$key] ?? $default_value;
        unset(static::$query_arr[$key]);
        return $out;
    }

    static function getParam($index){
        return static::$params[$index];
    } 

    public function raw()
    {
        return static::$raw;
    }

    static function getBody($assoc = true)
    {
        return json_decode(static::$raw, $assoc);
    }

    /*  ArrayAccess       */

    public function offsetSet($offset, $value) {
        if (is_null($offset)) {
            static::$params[] = $value;
        } else {
            static::$params[$offset] = $value;
        }
    }

    public function offsetExists($offset) {
        return isset(static::$params[$offset]);
    }

    public function offsetUnset($offset) {
        unset(static::$params[$offset]);
    }

    public function offsetGet($offset) {
        return isset(static::$params[$offset]) ? static::$params[$offset] : null;
    }

    /* Arrayable Interface */ 

    public function toArray(){
        return static::$params;
    }

}