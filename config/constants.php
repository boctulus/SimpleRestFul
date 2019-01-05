<?php

if (!defined('ROOT_PATH'))
	define('ROOT_PATH', dirname(__DIR__) . '/');

if (!defined('CORE_PATH'))
    define('CORE_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'core/');
    
if (!defined('MODELS_PATH'))
define('MODELS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'models/');    

// helpers at root level
if (!defined('HELPERS_PATH'))
define('HELPERS_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'helpers/');   

if (!defined('API_PATH'))
define('API_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'api/'); 

if (!defined('VENDOR_PATH'))
define('VENDOR_PATH', ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor/'); 