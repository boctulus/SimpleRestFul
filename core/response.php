<?php

/** 
 * Not in use !
 */
class Response
{
    protected $body;
    protected $headers = [];
    protected $http_code = 200;
    protected $http_code_msg = '';

    function __construct() {}
    
    /**
     * Add headers
     *
     * @return  self
     */ 
    public function addHeaders($headers)
    {
        $this->headers = $headers;
        return $this;
    }

    /**
     * Add header
     *
     * @return  self
     */ 
    public function addHeader($header)
    {
        $this->headers[] = $header;
        return $this;
    }

    /**
     * Set the value of http_code
     *
     * @return  self
     */ 
    public function code($http_code, $msg)
    {
        $this->$http_code_msg = $msg;
        $this->http_code = $http_code;
        return $this;
    }

    public function send($data, $http_code = NULL){
        $http_code = $http_code != NULL ? $http_code : $this->http_code_msg;
        
        if ($http_code != NULL)
            header(trim("HTTP/1.0 $this->http_code $this->http_code_msg"));
        
        if (is_array($data))
            $data = json_encode($data);

        echo $data; 
        exit();  	
    }

    function sendError($msg_error, $http_code = null){
        $this->send(['error' => $msg_error], $http_code);
    }
}