<?php

function assets($resource){
    /*
    if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
        $protocol = 'https:';
    } else {
        $protocol = 'http:';
    }
    */

    $config = include CONFIG_PATH . 'config.php';
    return '//' . $_SERVER['HTTP_HOST'].$config['BASE_URL'].'assets/'.$resource;
}

function section($view){
    include VIEWS_PATH . $view;
}