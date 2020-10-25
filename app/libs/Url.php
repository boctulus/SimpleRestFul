<?php

namespace simplerest\libs;

class Url {

    static function protocol(){
        /*
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $protocol = 'https:';
        } else {
            $protocol = 'http:';
        }
        */

        $config = include CONFIG_PATH . 'config.php';
        
        if ($config['HTTPS'] == 1 || strtolower($config['HTTPS']) == 'on'){
            $protocol = 'https:';
        } else {
            $protocol = 'http:';
        }

        return $protocol;
    }

    /**
     * url_check - complement for parse_url
     *
     * @param  string $url
     *
     * @return bool
     */
    static function url_check(string $url){
        $sym = null;
    
        $len = strlen($url);
        for ($i=0; $i<$len; $i++){
            if ($url[$i] == '?'){
                if ($sym == '?' || $sym == '&')
                    return false;
    
                $sym = '?';
            }elseif ($url[$i] == '&'){
                if ($sym === null)
                    return false;
    
                $sym = '&';
            } 
        }
        return true;
    }

    static function is_postman(){
		return (isset($_SERVER['HTTP_USER_AGENT']) && strpos($_SERVER['HTTP_USER_AGENT'], 'PostmanRuntime') !== false);	
	}
}

