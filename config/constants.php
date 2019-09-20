<?php
declare(strict_types=1);

if (!defined('ROOT_PATH'))
	define('ROOT_PATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);

if (!defined('CONFIG_PATH'))
	define('CONFIG_PATH', ROOT_PATH  . 'config' . DIRECTORY_SEPARATOR);

if (!defined('CORE_PATH'))
    define('CORE_PATH', ROOT_PATH . 'core'. DIRECTORY_SEPARATOR);
    
if (!defined('MODELS_PATH'))
    define('MODELS_PATH', ROOT_PATH . 'models'. DIRECTORY_SEPARATOR);    

if (!defined('VIEWS_PATH'))
    define('VIEWS_PATH', ROOT_PATH .  'views' . DIRECTORY_SEPARATOR);  

if (!defined('CONTROLLERS_PATH'))
    define('CONTROLLERS_PATH', ROOT_PATH . 'controllers' . DIRECTORY_SEPARATOR);  

if (!defined('ASSETS_PATH'))
    define('ASSETS_PATH', ROOT_PATH . 'assets' . DIRECTORY_SEPARATOR); 

// helpers at root level
if (!defined('HELPERS_PATH'))
    define('HELPERS_PATH', ROOT_PATH . 'helpers' . DIRECTORY_SEPARATOR);   

if (!defined('API_PATH'))
    define('API_PATH', CONTROLLERS_PATH . 'api' . DIRECTORY_SEPARATOR); 

if (!defined('LIBS_PATH'))
    define('LIBS_PATH', ROOT_PATH . 'libs' . DIRECTORY_SEPARATOR);   

if (!defined('VENDOR_PATH'))
    define('VENDOR_PATH', ROOT_PATH . 'vendor'. DIRECTORY_SEPARATOR); 

if (!defined('UPLOADS_PATH'))
    define('UPLOADS_PATH', ROOT_PATH . 'uploads'. DIRECTORY_SEPARATOR); 