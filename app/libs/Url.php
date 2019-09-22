<?php

namespace simplerest\libs;

class Url {
    static function assets($resource){
        if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
            $protocol = 'https://';
        } else {
            $protocol = 'http://';
        }
    
        $config = include INCLUDE_PATH . 'config.php';
        return $protocol.$_SERVER['HTTP_HOST'].'/'.$config['BASE_URL'].'assets/'.$resource;
    }
    
    static function section($view){
        include VIEWS_PATH . $view;
    }
}

