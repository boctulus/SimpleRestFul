<?php


class Response
{
    static protected $headers = [];
    static protected $http_code = NULL;
    static protected $http_code_msg = '';
    static protected $instance = NULL;


    protected function __construct() { }

    static function getInstance(){
        if(static::$instance == NULL){
            static::$instance = new static();
        }
        return static::$instance;
    }

    public function addHeaders(array $headers)
    {
        static::$headers = $headers;
        return static::getInstance();
    }
  
    public function addHeader(string $header)
    {
        static::$headers[] = $header;
        return static::getInstance();
    }

    public function code(int $http_code, string $msg = NULL)
    {
        static::$http_code_msg = $msg;
        static::$http_code = $http_code;
        return static::getInstance();
    }

    public function send($data, int $http_code = NULL){
        $http_code = $http_code != NULL ? $http_code : static::$http_code;
        
        if ($http_code != NULL)
            header(trim("HTTP/1.0 ".$http_code.' '.static::$http_code_msg));
        
        if (is_array($data) || is_object($data))
            $data = json_encode($data);

        echo $data; 
        exit();  	
    }

    function error(string $msg_error, int $http_code = null){
        $this->send(['error' => $msg_error], $http_code);
    }
}