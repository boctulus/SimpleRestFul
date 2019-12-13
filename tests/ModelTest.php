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
use simplerest\libs\Validator;
use simplerest\core\exceptions\InvalidValidationException;

include 'config/constants.php';

class ModelTest extends TestCase
{   		
  public function testget()
  {
    //
    $query = Database::table('products');
    $this->assertEquals($query->dd(), 'SELECT * FROM products WHERE deleted_at IS NULL');

    //
    $query = Database::table('products')->showDeleted();
    $this->assertEquals($query->dd(), 'SELECT * FROM products');

    //  
    $query = Database::table('products')->select(['size'])->distinct();
    $this->assertEquals($query->dd(), 'SELECT DISTINCT size FROM products WHERE deleted_at IS NULL');

    // 
    $query = Database::table('products')->select(['size', 'cost'])->distinct();
    $this->assertEquals($query->dd(), 'SELECT DISTINCT size, cost FROM products WHERE deleted_at IS NULL');

    // 
    $query = Database::table('products')->oldest();
    $this->assertEquals($query->dd(), 'SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at DESC');

    // 
    $query = Database::table('products')->newest();
    $this->assertEquals($query->dd(), 'SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at ASC');

    //  
    $query = Database::table('products')->random()->select(['id', 'name']);
    $this->assertEquals($query->dd(), 'SELECT id, name FROM products WHERE deleted_at IS NULL ORDER BY RAND()');

    Database::table('products')->showDeleted()
    ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
    ->where(['size', '1L'])
    ->orderBy(['size' => 'desc', 'cost' => 'asc'])
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT id, name, size, cost, belongs_to FROM products WHERE size = '1L' ORDER BY size DESC, cost ASC");

    // 
    $query = Database::table('users')
    ->where([ 'belongs_to'=> 160] )
    ->count();
    $this->assertEquals(Database::getQueryLog(), 'SELECT COUNT(*) FROM users WHERE belongs_to = 160 AND deleted_at IS NULL');

    // 
    $query = Database::table('users')
    ->where([ 'belongs_to'=> 160] )
    ->count();
    $this->assertEquals(Database::getQueryLog(), 'SELECT COUNT(*) FROM users WHERE belongs_to = 160 AND deleted_at IS NULL');;

    //  
    Database::table('products')->showDeleted()
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->count('modified_at');
    $this->assertEquals(Database::getQueryLog(), "SELECT COUNT(modified_at) FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90)");;

    //  
    Database::table('products')->showDeleted()
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->distinct()
    ->count('description');
    $this->assertEquals(Database::getQueryLog(), "SELECT COUNT(DISTINCT description) FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90)");;

    //  
    $query = Database::table('products')
    ->where([ [ 'cost', 200, '>='], [ 'size', '2L'] ], 'OR')
    ->count();
    $this->assertEquals(Database::getQueryLog(), "SELECT COUNT(*) FROM products WHERE (cost >= 200 OR size = '2L') AND deleted_at IS NULL");;

    //  
    $query = Database::table('products')->showDeleted()
    ->where([ [ 'cost', 200, '>='], [ 'size', '2L'] ], 'OR')
    ->count();
    $this->assertEquals(Database::getQueryLog(), "SELECT COUNT(*) FROM products WHERE (cost >= 200 OR size = '2L')");

    //  
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->distinct()
    ->count('description');
    $this->assertEquals(Database::getQueryLog(), "SELECT COUNT(DISTINCT description) FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");;

    // 
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->avg('cost');
    $this->assertEquals(Database::getQueryLog(), "SELECT AVG(cost) FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");;

    // 
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->sum('cost');
    $this->assertEquals(Database::getQueryLog(), "SELECT SUM(cost) FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");;

    //  
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->min('cost');
    $this->assertEquals(Database::getQueryLog(), "SELECT MIN(cost) FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");;

    // 
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->max('cost');
    $this->assertEquals(Database::getQueryLog(), "SELECT MAX(cost) FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");

    // 
    Database::table('products')->orderBy(['cost' => 'DESC'])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost DESC");        

    // 
    Database::table('products')->limit(10)->offset(20)->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL LIMIT 20, 10");

    // 
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->limit(10)->offset(20)
    ->get(['cost']);
    $this->assertEquals(Database::getQueryLog(), "SELECT cost FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL LIMIT 20, 10");

    // 
    Database::table('products')->random()->select(['id', 'name'])->addSelect('cost')->first();
    $this->assertEquals(Database::getQueryLog(), "SELECT id, name, cost FROM products WHERE deleted_at IS NULL ORDER BY RAND() LIMIT 0, 1");

    // 
    Database::table('products')->setFetchMode('COLUMN')
    ->selectRaw('cost * ? as cost_after_inc', [1.05])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT cost * 1.05 as cost_after_inc FROM products WHERE deleted_at IS NULL");

    // 
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->selectRaw('cost * ? as cost_after_inc', [1.05])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT cost * 1.05 as cost_after_inc FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");

    //  
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT DISTINCT cost * 1.05 as cost_after_inc, name, description, size, cost, workspace, active, locked, belongs_to FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");

    // 
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->select(['name', 'size'])
    ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT DISTINCT cost * 1.05 as cost_after_inc, name, size FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");

    // 
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()
    ->addSelect('name')
    ->addSelect('size')
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT DISTINCT cost * 1.05 as cost_after_inc, name, size FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");

    //   
    Database::table('products')
    ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
    ->selectRaw('cost * ? as cost_after_inc', [1.05])
    ->addSelect('name')
    ->addSelect('cost')
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT cost * 1.05 as cost_after_inc, name, cost FROM products WHERE (cost >= 100 AND size = '1L' AND belongs_to = 90) AND deleted_at IS NULL");

    // 
    Database::table('products')->showDeleted()
    ->groupBy(['size'])->select(['size'])->count();
    $this->assertEquals(Database::getQueryLog(), "SELECT size, COUNT(*) FROM products GROUP BY size");

    // 
    Database::table('products')->showDeleted()
    ->groupBy(['size'])->select(['size'])
    ->avg('cost');
    $this->assertEquals(Database::getQueryLog(), "SELECT size, AVG(cost) FROM products GROUP BY size");

    // 
    Database::table('products')->showDeleted()->where([ 
      ['size', '2L']
    ])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size = '2L'");

    // 
    Database::table('products')->showDeleted()->where([ 
      'size', '2L'
    ])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size = '2L'");

    //  
    Database::table('products')->showDeleted()->where( 
      ['size' => '2L']
    )->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size = '2L'");

    // 
    Database::table('products')
    ->where([ 
      ['name', ['Vodka', 'Wisky', 'Tekila','CocaCola']], // IN 
      ['locked', 0],
      ['belongs_to', 90]
    ])
    ->whereNotNull('description')
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE (name IN ('Vodka', 'Wisky', 'Tekila', 'CocaCola') AND locked = 0 AND belongs_to = 90) AND description IS NOT NULL AND deleted_at IS NULL");

    // 
    Database::table('products')->where([ 
      ['name', ['CocaCola', 'PesiLoca']], 
      ['cost', 550, '>='],
      ['cost', [100, 200]]
    ], 'OR')->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE (name IN ('CocaCola', 'PesiLoca') OR cost IN (100, 200) OR cost >= 550) AND deleted_at IS NULL");

    // 
    Database::table('products')->where([ 
      ['name', ['CocaCola', 'PesiLoca', 'Wisky', 'Vodka'], 'NOT IN']
    ])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE name NOT IN ('CocaCola', 'PesiLoca', 'Wisky', 'Vodka') AND deleted_at IS NULL");

    //  
    Database::table('products')->where([ 
        ['cost', 200, '<'],
        ['name', 'CocaCola'] 
    ])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE (cost < 200 AND name = 'CocaCola') AND deleted_at IS NULL");

    //  
    Database::table('products')->where([ 
        ['cost', 200, '>='],
        ['cost', 270, '<=']
    ])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE (cost >= 200 AND cost <= 270) AND deleted_at IS NULL");

    //  
    Database::table('products')->where(['size', ['0.5L', '3L'], 'IN'])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size IN ('0.5L', '3L') AND deleted_at IS NULL");

    // 
    Database::table('products')->where(['size', ['0.5L', '3L']])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size IN ('0.5L', '3L') AND deleted_at IS NULL");

    //  
    Database::table('products')->whereIn('size', ['0.5L', '3L'])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size IN ('0.5L', '3L') AND deleted_at IS NULL");

    // 
    Database::table('products')->where(['size', ['0.5L', '3L'], 'NOT IN'])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size NOT IN ('0.5L', '3L') AND deleted_at IS NULL");

    // 
    Database::table('products')->whereNotIn('size', ['0.5L', '3L'])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE size NOT IN ('0.5L', '3L') AND deleted_at IS NULL");

    //  
    Database::table('products')->where(['workspace', null])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE workspace IS NULL AND deleted_at IS NULL");

    //  
    Database::table('products')->whereNull('workspace')->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE workspace IS NULL AND deleted_at IS NULL");

    // 
    Database::table('products')->where(['workspace', null, 'IS NOT'])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE workspace IS NOT NULL AND deleted_at IS NULL");

    //  
    Database::table('products')->whereNotNull('workspace')->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE workspace IS NOT NULL AND deleted_at IS NULL");

    // 
    Database::table('products')
    ->select(['name', 'cost'])
    ->whereBetween('cost', [100, 250])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT name, cost FROM products WHERE cost >= 100 AND cost <= 250 AND deleted_at IS NULL");

    //  
    Database::table('products')
    ->select(['name', 'cost'])
    ->whereNotBetween('cost', [100, 250])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT name, cost FROM products WHERE (cost < 100 OR cost > 250) AND deleted_at IS NULL");

    //  
    Database::table('products')
    ->find(103);
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE id = 103 AND deleted_at IS NULL");

    // 
    Database::table('products')
    ->where(['cost', 150])
    ->value('name');
    $this->assertEquals(Database::getQueryLog(), "SELECT name FROM products WHERE cost = 150 AND deleted_at IS NULL LIMIT 0, 1");

    //  
    Database::table('products')->showDeleted()
    ->select(['name', 'cost', 'id'])
    ->where(['belongs_to', 90])
    ->where([ 
        ['cost', 100, '>='],
        ['cost', 500, '<']
    ])
    ->whereNotNull('description')
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT name, cost, id FROM products WHERE belongs_to = 90 AND (cost >= 100 AND cost < 500) AND description IS NOT NULL");

      //   
    Database::table('products')->showDeleted()
    ->select(['name', 'cost', 'id'])
    ->where(['belongs_to', 90])
    ->where([ 
        ['name', ['CocaCola', 'PesiLoca']], 
        ['cost', 550, '>='],
        ['cost', 100, '<']
    ], 'OR')
    ->whereNotNull('description')
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT name, cost, id FROM products WHERE belongs_to = 90 AND (name IN ('CocaCola', 'PesiLoca') OR cost >= 550 OR cost < 100) AND description IS NOT NULL");

    // 
    Database::table('products')->showDeleted()
    ->select(['name', 'cost', 'id'])
    ->where(['belongs_to', 90])
    ->orWhere(['name', ['CocaCola', 'PesiLoca']])
    ->orWhere([
        ['cost', 550, '<='],
        ['cost', 100, '>=']
    ])
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT name, cost, id FROM products WHERE belongs_to = 90 OR name IN ('CocaCola', 'PesiLoca') OR (cost <= 550 AND cost >= 100)");

    //  
    Database::table('products')->showDeleted()
    ->select(['name', 'cost', 'id', 'description'])
    ->whereNotNull('description')
    ->orWhere([ 
                ['cost', 100, '>='],
                ['cost', 500, '<']
    ])        
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT name, cost, id, description FROM products WHERE description IS NOT NULL OR (cost >= 100 AND cost < 500)");

    // 
    Database::table('products')
    ->select(['id', 'name', 'cost', 'description'])
    ->where(['belongs_to', 90])
    ->where([ 
        ['name', ['CocaCola', 'PesiLoca']], 
        ['cost', 550, '>='],
        ['cost', 100, '<']
    ], 'OR')
    ->whereNotNull('description')
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT id, name, cost, description FROM products WHERE belongs_to = 90 AND (name IN ('CocaCola', 'PesiLoca') OR cost >= 550 OR cost < 100) AND description IS NOT NULL AND deleted_at IS NULL");   

      //  
    Database::table('users')->unhide(['password'])
      ->where([ 'email'=> 'nano@g.c', 
                'username' => 'nano@g.c' 
      ], 'OR') 
      ->setValidator((new Validator())->setRequired(false))  
      ->get();

    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM users WHERE (email = 'nano@g.c' OR username = 'nano@g.c') AND deleted_at IS NULL");

    //  
    $rows = Database::table('users')
        ->where([ 'email'=> 'nano@g.c' ]) 
        ->orWhere(['username' => 'nano' ])
        ->setValidator((new Validator())->setRequired(false))  
        ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT id, username, email, confirmed_email, firstname, lastname, deleted_at, belongs_to FROM users WHERE email = 'nano@g.c' OR username = 'nano' AND deleted_at IS NULL");

    // 
    Database::table('products')
    ->where(['belongs_to' => 90])
    ->whereRaw('cost < IF(size = "1L", ?, 100) AND size = ?', [300, '1L'])
    ->orderBy(['cost' => 'ASC'])
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE (cost < IF(size = \"1L\", 300, 100) AND size = '1L') AND belongs_to = 90 AND deleted_at IS NULL ORDER BY cost ASC");

    // 
    Database::table('products')->showDeleted()
    ->whereRaw('EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?  )', ['AB'])
    ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = 'AB' )");   

    // 
    Database::table('products')->showDeleted()
      ->whereExists('(SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?)', ['AB'])
      ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = 'AB')");

    // 
    Database::table('products')->orderBy(['cost'=>'ASC', 'id'=>'DESC'])->take(4)->offset(1)->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4");

    // 
    Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4");

    // S
    Database::table('products')->orderBy(['cost'=>'ASC'])->take(4)->offset(1)->get(null, ['id'=>'DESC']);
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4");

    // 
    Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4");

    // 
    Database::table('products')->take(4)->offset(1)->get(null, ['cost'=>'ASC', 'id'=>'DESC']);
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4");

    // 
    Database::table('products')->orderByRaw('locked * active DESC')->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE deleted_at IS NULL ORDER BY locked * active DESC");

    // 
    Database::table('products')->where([ 
      ['cost', 100, '>=']
    ])->orderBy(['size' => 'DESC'])->groupBy(['size'])->select(['size'])->avg('cost');
    $this->assertEquals(Database::getQueryLog(), "SELECT size, AVG(cost) FROM products WHERE cost >= 100 AND deleted_at IS NULL GROUP BY size ORDER BY size DESC");

    //  
    Database::table('products')->where([ 
      ['cost', 200, '>='],
      ['cost', 270, '<='],
      ['belongs_to',  90]
    ])->get(); 
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE (cost >= 200 AND cost <= 270 AND belongs_to = 90) AND deleted_at IS NULL");

    //  
    Database::table('products')
    ->where([ 
          ['cost', 150, '>='],
          ['cost', 270, '<=']            
      ])
    ->where(['belongs_to' =>  90])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE (cost >= 150 AND cost <= 270) AND belongs_to = 90 AND deleted_at IS NULL");		

	Database::table('products')->showDeleted()
            ->groupBy(['name'])
            ->having(['c', 3, '>'])
            ->select(['name'])
			->selectRaw('COUNT(*) as c')
			->get();
	$this->assertEquals(Database::getQueryLog(), "SELECT COUNT(*) as c, name FROM products GROUP BY name HAVING c > 3");	

	Database::table('products')
            ->groupBy(['name'])
            ->having(['c', 3, '>='])
            ->select(['name'])
			->selectRaw('COUNT(name) as c')
			->get();
			  
	$this->assertEquals(Database::getQueryLog(), "SELECT COUNT(name) as c, name FROM products WHERE id IN (SELECT id FROM products WHERE deleted_at IS NULL) GROUP BY name HAVING c >= 3");		
		
    // 
    Database::table('products')
      ->groupBy(['cost', 'size'])
      ->having(['cost', 100])
      ->get(['cost', 'size']);
    $this->assertEquals(Database::getQueryLog(), "SELECT cost, size FROM products WHERE id IN (SELECT id FROM products WHERE deleted_at IS NULL) GROUP BY cost,size HAVING cost = 100");

	// 
    Database::table('products')->showDeleted()
      ->groupBy(['cost', 'size'])
      ->having(['cost', 100])
      ->get(['cost', 'size']);
    $this->assertEquals(Database::getQueryLog(), "SELECT cost, size FROM products GROUP BY cost,size HAVING cost = 100");
	
    // 
    Database::table('products')->showDeleted()
      ->groupBy(['cost', 'size', 'belongs_to'])
      ->having(['belongs_to', 90])
      ->having([  
                  ['cost', 100, '>='],
                  ['size' => '1L'] ], 
      'OR')
      ->orderBy(['size' => 'DESC'])
      ->get(['cost', 'size', 'belongs_to']); 
    $this->assertEquals(Database::getQueryLog(), "SELECT cost, size, belongs_to FROM products GROUP BY cost,size,belongs_to HAVING belongs_to = 90 AND (cost >= 100 OR size = '1L') ORDER BY size DESC");

    // 
    Database::table('products')->showDeleted()
      ->groupBy(['cost', 'size', 'belongs_to'])
      ->having(['belongs_to', 90])
      ->orHaving(['cost', 100, '>='])
      ->orHaving(['size' => '1L'])
      ->orderBy(['size' => 'DESC'])
      ->get(['cost', 'size', 'belongs_to']); 
    $this->assertEquals(Database::getQueryLog(), "SELECT cost, size, belongs_to FROM products GROUP BY cost,size,belongs_to HAVING belongs_to = 90 OR cost >= 100 OR size = '1L' ORDER BY size DESC");

    // 
    Database::table('products')->showDeleted()
      ->groupBy(['cost', 'size', 'belongs_to'])
      ->having(['belongs_to', 90])
      ->orHaving([  
                  ['cost', 100, '>='],
                  ['size' => '1L'] ] 
      )
      ->orderBy(['size' => 'DESC'])
      ->get(['cost', 'size', 'belongs_to']); 
    $this->assertEquals(Database::getQueryLog(), "SELECT cost, size, belongs_to FROM products GROUP BY cost,size,belongs_to HAVING belongs_to = 90 OR (cost >= 100 AND size = '1L') ORDER BY size DESC");

    // 
    Database::table('products')
      ->selectRaw('SUM(cost) as total_cost')
      ->where(['size', '1L'])
      ->groupBy(['belongs_to']) 
      ->havingRaw('SUM(cost) > ?', [500])
      ->limit(3)
      ->offset(1)
      ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT SUM(cost) as total_cost FROM products WHERE size = '1L' AND deleted_at IS NULL GROUP BY belongs_to HAVING SUM(cost) > 500 LIMIT 1, 3");

    // 
    $o = Database::table('other_permissions', 'op');
    $o->join('folders', 'op.folder_id', '=',  'folders.id')
              ->join('users', 'folders.belongs_to', '=', 'users.id')
              ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
              ->where([
                  ['guest', 1],
                  ['resource_table', 'products'],
                  ['r', 1]
              ])
              ->orderByRaw('users.id DESC')
              ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM other_permissions as op INNER JOIN folders ON op.folder_id=folders.id INNER JOIN users ON folders.belongs_to=users.id INNER JOIN user_roles ON users.id=user_roles.user_id WHERE (guest = 1 AND resource_table = 'products' AND r = 1) ORDER BY users.id DESC");

    //  
    Database::table('products')->where(['workspace', null])->get();  
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE workspace IS NULL AND deleted_at IS NULL");	

    //  
    Database::table('products')->whereNull('workspace')->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT * FROM products WHERE workspace IS NULL AND deleted_at IS NULL");     

    //  
    Database::table('products')->showDeleted()
      ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
      ->whereRaw('belongs_to IN (SELECT id FROM users WHERE password IS NULL)')
      ->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL)");
    
	}	
  
  function testsubqueries(){
    //  
    $sub = Database::table('users')
    ->select(['id'])
    ->whereRaw('password IS NULL');

    Database::table('products')->showDeleted()
    ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
    ->whereRaw("belongs_to IN ({$sub->toSql()})")
    ->get();

    $this->assertEquals(Database::getQueryLog(), "SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL AND deleted_at IS NULL)");   

    //  
    $sub = Database::table('users')->showDeleted()
    ->select(['id'])
    ->whereRaw('confirmed_email = 1')
    ->where(['password', 100, '<']);

    $res = Database::table('products')->showDeleted()
    ->mergeBindings($sub)
    ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
    ->where(['size', '1L'])
    ->whereRaw("belongs_to IN ({$sub->toSql()})")
    ->get();

    $this->assertEquals(Database::getQueryLog(), "SELECT id, name, size, cost, belongs_to FROM products WHERE (belongs_to IN (SELECT id FROM users WHERE (confirmed_email = 1) AND password < 100)) AND size = '1L'");  

    // 
    $sub = Database::table('users')->showDeleted()
    ->selectRaw('users.id')
    ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
    ->whereRaw('confirmed_email = 1')
    ->where(['password', 100, '<'])
    ->where(['role_id', 2]);

    $res = Database::table('products')->showDeleted()
    ->mergeBindings($sub)
    ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
    ->where(['size', '1L'])
    ->whereRaw("belongs_to IN ({$sub->toSql()})")
    ->orderBy(['id' => 'desc'])
    ->get();

    $this->assertEquals(Database::getQueryLog(), "SELECT id, name, size, cost, belongs_to FROM products WHERE (belongs_to IN (SELECT users.id FROM users INNER JOIN user_roles ON users.id=user_roles.user_id WHERE (confirmed_email = 1) AND password < 100 AND role_id = 2)) AND size = '1L' ORDER BY id DESC");    
    
    //  
    $sub = Database::table('users')->showDeleted()
    ->selectRaw('users.id')
    ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
    ->whereRaw('confirmed_email = 1')
    ->where(['password', 100, '<'])
    ->where(['role_id', 3]);

    $res = Database::table('products')->showDeleted()
    ->mergeBindings($sub)
    ->select(['size'])
    ->whereRaw("belongs_to IN ({$sub->toSql()})")
    ->groupBy(['size'])
    ->avg('cost');

    $this->assertEquals(Database::getQueryLog(), "SELECT size, AVG(cost) FROM products WHERE belongs_to IN (SELECT users.id FROM users INNER JOIN user_roles ON users.id=user_roles.user_id WHERE (confirmed_email = 1) AND password < 100 AND role_id = 3) GROUP BY size");

    //   
    $sub = Database::table('products')->showDeleted()
    ->select(['size'])
    ->groupBy(['size']);

    $conn = Database::getConnection();

    $m = new \simplerest\core\Model($conn);
    $res = $m->fromRaw("({$sub->toSql()}) as sub")->count();

    $this->assertEquals(trim(preg_replace('!\s+!', ' ',$m->getLastPrecompiledQuery())), "SELECT COUNT(*) FROM (SELECT size FROM products GROUP BY size) as sub");

    // 
    $sub = Database::table('products')->showDeleted()
    ->select(['size'])
    ->where(['belongs_to', 90])
    ->groupBy(['size']);

    $res = Database::table("({$sub->toSql()}) as sub")
    ->mergeBindings($sub)
    ->count();

    $this->assertEquals(trim(preg_replace('!\s+!', ' ',Database::getQueryLog())), "SELECT COUNT(*) FROM (SELECT size FROM products WHERE belongs_to = 90 GROUP BY size) as sub");
  }

  function testunion(){   
    // 
    $uno = Database::table('products')->showDeleted()
    ->select(['id', 'name', 'description', 'belongs_to'])
    ->where(['belongs_to', 90]);

    $m2  = Database::table('products')->showDeleted();
    $dos = $m2
    ->select(['id', 'name', 'description', 'belongs_to'])
    ->where(['belongs_to', 4])
    ->where(['cost', 200, '>='])
    ->union($uno)
    ->orderBy(['id' => 'ASC'])
    ->offset(20)
    ->limit(10)
    ->get();

    $this->assertEquals(preg_replace('!\s+!', ' ',$m2->getLastPrecompiledQuery()), "SELECT id, name, description, belongs_to FROM products WHERE belongs_to = ? AND cost >= ? UNION SELECT id, name, description, belongs_to FROM products WHERE belongs_to = ? ORDER BY id ASC LIMIT ?, ?");
  }

  function testdelete(){
    $u = Database::table('users');
    $u->where(['id' => 100000])->delete(false);
    $this->assertEquals(Database::getQueryLog(), "DELETE FROM users WHERE id = 100000");
  }

  function testcreate(){       
    $id = Database::table('users')->create(['email'=> 'doe2000@g.com', 'password'=>'pass', 'firstname'=>'Jhon', 'lastname'=>'Doe', 'username' => 'doe2000']);
    $this->assertEquals(Database::getQueryLog(), "INSERT INTO users (email, password, firstname, lastname, username) VALUES ('doe2000@g.com', 'pass', 'Jhon', 'Doe', 'doe2000')");
    
    $ok = (bool) Database::table('users')->where(['id' => $id])->delete(false);        
    $this->assertTrue($ok);
  }
  
  function testupdate(){
    $u = Database::table('users');
    $u->where(['id' => 100000])->update(['firstname'=>'Nico', 'lastname'=>'Buzzi']);
    $this->assertEquals(Database::getQueryLog(), "UPDATE users SET firstname = 'Nico', lastname = 'Buzzi' WHERE id = 100000");

    $u->where([ ['lastname', ['AAA', 'Buzzi']] ])->update(['firstname'=>'Nicolay']);
    $this->assertEquals(Database::getQueryLog(), "UPDATE users SET firstname = 'Nicolay' WHERE id = 100000 AND lastname IN ('AAA', 'Buzzi')");
  }

  function testhide(){
    $u = Database::table('users');
    $u->unhide(['password']);
    $u->hide(['username', 'confirmed_email', 'firstname','lastname', 'deleted_at', 'belongs_to']);
    $u->where(['id'=> 100000])->get();
    $this->assertEquals(Database::getQueryLog(), "SELECT id, email, password FROM users WHERE id = 100000 AND deleted_at IS NULL");
  }

  function testfill1(){ 
    $this->expectException(\InvalidArgumentException::class);
    $u = Database::table('users');
    $id = $u->create(['email'=> 'testing@g.com', 'password'=>'pass', 'firstname'=>'Jhon', 'lastname'=>'Doe', 'confirmed_email' => 1]);
  }

  function testfill2(){
    $this->expectException(\InvalidArgumentException::class);
    $u = Database::table('users');
    $u->unfill(['password']);
    $id = $u->create(['email'=> 'testing@g.com', 'password'=>'pass', 'firstname'=>'Jhon', 'lastname'=>'Doe']);
  }

}
