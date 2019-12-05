<?php

namespace simplerest\tests;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '../../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use simplerest\libs\Factory;
use simplerest\libs\Arrays;
use simplerest\libs\Database;
use simplerest\libs\Debug;
use simplerest\libs\Url;
use simplerest\models\UsersModel;

include 'config/constants.php';

class ModelTest extends TestCase
{   		
  public function testget()
  {
		  $this->assertIsArray(Database::table('products')->get());
	}	
	
}
