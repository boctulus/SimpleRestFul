<?php

namespace simplerest\core;

class Response
{
    static protected $headers = [];
    static protected $http_code = NULL;
    static protected $http_code_msg = '';
    static protected $instance = NULL;
    static protected $version = '1.1';
    static protected $config;
    static protected $pretty;


    protected function __construct() { 
        static::$config = include CONFIG_PATH . 'config.php';
        static::$pretty = static::$config['pretty'];
    }

    static function getInstance(){
        
        if(static::$instance == NULL){
            static::$instance = new static();
        }
        return static::$instance;
    }
    
    function redirect(string $url){
        if (!headers_sent($filename, $line)) {
            header("Location: $url");
            exit;
        }else
            throw new \Exception("Headers already sent in in $filename on line $line. Unable to redirect to $url");
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

    public function setPretty($state){
        static::$pretty = $state;
        return static::getInstance();
    }

    public function encode($data){
        $options = 0;

        if (static::$pretty)
            $options = $options | JSON_PRETTY_PRINT;
            
        return json_encode($data, $options);  
    }

    public function send($data, int $http_code = NULL){
        $http_code = $http_code != NULL ? $http_code : static::$http_code;
        
        if ($http_code == NULL)
          static::$http_code;

        if ($http_code != NULL)
            header(trim('HTTP/'.static::$version.' '.$http_code.' '.static::$http_code_msg));
        
        if (is_array($data) || is_object($data))
            $data = $this->encode($data);

        echo $data . "\n"; 
        exit;  	
    }

    function sendCode(int $http_code){
        http_response_code($http_code);
        exit;
    }
 
    // send as JSON
    public function sendJson($data, int $http_code = NULL){
        $http_code = $http_code != NULL ? $http_code : static::$http_code;
        
        if ($http_code != NULL)
            header(trim('HTTP/'.static::$version.' '.$http_code.' '.static::$http_code_msg));
       
        echo $this->encode($data). "\n"; 
        exit;  	
    }

    // send error
    function sendError(string $msg_error, int $http_code = NULL){
        if ($http_code == NULL)
            if (static::$http_code != NULL)
                $http_code = static::$http_code;
            else
                $http_code = 500;

        if ($http_code != NULL)
            header(trim('HTTP/'.static::$version.' '.$http_code.' '.static::$http_code_msg));

        echo json_encode(['error' => $msg_error], $http_code) . "\n";
        exit;
    }
}