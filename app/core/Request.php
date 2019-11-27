<?php

namespace simplerest\core; 

use simplerest\core\interfaces\Arrayable;

class Request  implements \ArrayAccess, Arrayable
{
    static protected $query_arr;
    static protected $raw;
    static protected $params;
    static protected $headers;
    static protected $instance = NULL;

    protected function __construct() { }

    static function getInstance(){
        if(static::$instance == NULL){
            if (php_sapi_name() != 'cli'){
                if (isset($_SERVER['QUERY_STRING']))
					parse_str($_SERVER['QUERY_STRING'], static::$query_arr);
				
                static::$raw = file_get_contents("php://input");
                static::$headers = apache_request_headers();
            }
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function setParams($params){
        static::$params = $params;
        return static::getInstance();
    }

    public function headers(){
        return static::$headers;
    }

    public function header($key){
        return static::$headers[$key] ?? NULL;
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

    public function getParam($index){
        return static::$params[$index];
    } 

    public function raw()
    {
        return static::$raw;
    }

    function getBody($assoc = true)
    {
        return json_decode(static::$raw, $assoc);
    }

    function getBodyParam($key){
        return $this->getBody()[$key];
    }

    function getCode(){
        return http_response_code();
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