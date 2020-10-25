<?php

namespace simplerest\core;

use simplerest\libs\Factory;
use simplerest\libs\DB;

class Response
{
    static protected $headers = [];
    static protected $http_code = NULL;
    static protected $http_code_msg = '';
    static protected $instance = NULL;
    static protected $version = '2';
    static protected $config;
    static protected $pretty;
    static protected $quit = true;
    static protected $paginator;
    static protected $as_object = false;
    static protected $fake_status_codes = false; // send 200 instead
    static protected $options = JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES;


    protected function __construct() { 
        static::$config = include CONFIG_PATH . 'config.php';
        static::$pretty = static::$config['pretty'];
    }

    public function __destruct()
    {
        DB::closeAllConnections();
    }    

    static function getInstance(){
        
        if(static::$instance == NULL){
            static::$instance = new static();
        }
        return static::$instance;
    }
    
    function redirect(string $url){
        if (!headers_sent()) {
            header("Location: $url");
            exit;
        }else
            throw new \Exception("Headers already sent in in $filename on line $line. Unable to redirect to $url");
    }

    function asObject(bool $val = true){
        static::$as_object = $val;
    }

    function addHeaders(array $headers)
    {
        static::$headers = $headers;
        return static::getInstance();
    }
  
    function addHeader(string $header)
    {
        static::$headers[] = $header;
        return static::getInstance();
    }

    /**
     * sendHeaders
     *
     * @param  mixed $headers
     *
     * @return void
     */
    private function sendHeaders(array $headers = []) {
        foreach ($headers as $k => $val){
            if (empty($val))
                continue;
            
            header("${k}:$val");
        }
    }

    function code(int $http_code, string $msg = NULL)
    {
        static::$http_code_msg = $msg;
        static::$http_code = $http_code;
        return static::getInstance();
    }

    function setPretty(bool $state){
        static::$pretty = $state;
        return static::getInstance();
    }

    function setQuit(bool $state){
        static::$quit = $state;
        return static::getInstance();
    }

    function encode($data){        
        $options = static::$pretty ? static::$options | JSON_PRETTY_PRINT : static::$pretty;
            
        return json_encode($data, $options);  
    }

    function setPaginator(array $p){
        static::$paginator = $p;
        return static::getInstance();
    }

    private function zip($data){
        ob_start("ob_gzhandler");
        echo $data; 
        ob_end_flush();
    } 

    function send($data, int $http_code = NULL){
        $http_code = $http_code != NULL ? $http_code : (static::$http_code !== null ? static::$http_code : 200);

        if (!headers_sent()) {
            header(trim('HTTP/'.static::$version.' '.$http_code.' '.static::$http_code_msg));
        }    

        if (static::$as_object || is_object($data) || is_array($data)) {
            $arr = ['data' => $data, 
                    'status_code' => $http_code,
                    'error' => '', 
                    'error_detail' => '' 
            ];

            if (static::$paginator != NULL)
                $arr['paginator'] = static::$paginator;

            $data = $this->encode($arr);
            header('Content-type:application/json;charset=utf-8');
        }            

        //if (Factory::request()->gzip() && strlen($data) > 1000){
        //    $this->addHeader('Content-Encoding: gzip');
        //    $this->zip($data. "\n");
        //}else
        //    echo $data. "\n";

        echo $data;    

        if (static::$quit)
            exit;  	
    }

    function sendCode(int $http_code){
        echo json_encode(['status_code' => $http_code]);
          
        if (!static::$fake_status_codes){    
            http_response_code($http_code);
        }    

        if (static::$quit)
            exit; 
    }
 

    function sendOK(){
        if (!headers_sent()) {
            http_response_code(200);
        }
        exit;
    }

    // send as JSON
    function sendJson($data, int $http_code = NULL){
        $http_code = $http_code != NULL ? $http_code : (static::$http_code !== null ? static::$http_code : 200);
        
        if (!headers_sent()) {
            header(trim('HTTP/'.static::$version.' '.$http_code.' '.static::$http_code_msg));
            header('Content-type:application/json;charset=utf-8');
        }

        $res =  $this->encode([ 
                                'data' => $data, 
                                'status_code' => $http_code,
                                'error' => '', 
                                'error_detail' => ''
        ]). "\n"; 
        
        if (Factory::request()->gzip() && strlen($res) > 1000){
            $this->addHeader('Content-Encoding: gzip');
            $this->zip($res);    
        }
        else
            echo $res;

        if (static::$quit)
            exit; 
    }

   
    /**
     * sendError
     *
     * @param  string $msg_error
     * @param  int $http_code
     * @param  string $error_detail
     *
     * @return void
     */
    function sendError(string $msg_error, int $http_code = NULL, $error_detail= NULL){
        if (!headers_sent()) {
            if ($http_code == NULL)
                if (static::$http_code != NULL)
                    $http_code = static::$http_code;
                else
                    $http_code = 500;
    
            if ($http_code != NULL && !static::$fake_status_codes)
                header(trim('HTTP/'.static::$version.' '.$http_code.' '.static::$http_code_msg));
                header('Content-type:application/json;charset=utf-8');
        }    
        
        $res =  $this->encode([ 
                                'status_code' => $http_code,
                                'error' => $msg_error,
                                'error_detail' => $error_detail
        ], $http_code) . "\n";
        
        if (Factory::request()->gzip() && strlen($res) > 1000){
            $this->addHeader('Content-Encoding: gzip');
            $this->zip($res);    
        }
        else
            echo $res;
        
        if (static::$quit){
            exit;
        }
             
    }
}