<?php

namespace simplerest\controllers;

use simplerest\core\Controller;
use simplerest\core\Request;
use simplerest\libs\Factory;
use simplerest\libs\Debug;
use simplerest\libs\DB;
use simplerest\core\Model;
use simplerest\models\BarModel;
use simplerest\models\UsersModel;
use simplerest\models\ProductsModel;
use simplerest\models\UserRolesModel;
use PHPMailer\PHPMailer\PHPMailer;
use simplerest\libs\Utils;
use simplerest\libs\Strings;
use simplerest\libs\Validator;
//use GuzzleHttp\Client;
//use Guzzle\Http\Message\Request;
//use Symfony\Component\Uid\Uuid;
use simplerest\libs\Files;
use simplerest\libs\Time;
use simplerest\core\Schema;


class DumbController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index(){
        return 'INDEX';
    }

    function add($a, $b){
        $res = (int) $a + (int) $b;
        return  "$a + $b = " . $res;
    }

    function mul(){
        $req = Request::getInstance();
        $res = (int) $req[0] * (int) $req[1];
        return "$req[0] * $req[1] = " . $res;
    }

    function div(){
        $res = (int) @Request::getParam(0) / (int) @Request::getParam(1);
        //
        // hacer un return en vez de un "echo" me habilita a manipular
        // la "respuesta", conviertiendola a JSON por ejemplo 
        //
        return ['result' => $res];
    }

    function login(){
		$this->view('login.php');
	}
	

    /*
    function mul(Request $req){
        $res = (int) $req[0] * (int) $req[1];
        echo "$req[0] + $req[1] = " . $res;
    }
    */    

    function use_model(){
        $m = (new Model(true))
            ->table('products')  // <---------------- 
            ->select(['id', 'name', 'size'])
            ->where(['cost', 150, '>=']);

        Debug::dd($m->get());

        // No hay Schema
        Debug::dd($m->getSchema());
    }

    function get_bar0(){
        $m = (new Model(true))
            ->table('bar')  
            ->where(['uuid', '0fefc2b1-f0d3-47aa-a875-5dbca85855f9']);
            
        Debug::dd($m->get());
    }

    function get_bar1(){
        $m = DB::table('bar')
         // ->assoc()
        ->where(['uuid', '0fefc2b1-f0d3-47aa-a875-5dbca85855f9']);
       
        Debug::dd($m->get());
    }

    function get_bar2(){
        $m = (new BarModel())
        ->connect()
        // ->assoc()
        ->where(['uuid', '0fefc2b1-f0d3-47aa-a875-5dbca85855f9']);
        
        Debug::dd($m->get());
    }
    

    /*
        Se utiliza un modelo *sin* schema y sobre el cual no es posible hacer validaciones
    */
    function create_s(){
        $m = (new Model(true))
        ->table('super_cool_table');

        // No hay schema ?
        Debug::dd($m->getSchema());
        
        Debug::dd($m->create([
            'name' => 'SUPER',
			'age' => 22,
        ]));
    }

    /*
        Se utiliza un modelo *sin* schema y sobre el cual no es posible hacer validaciones
    */
    function create_baz0(){
        $m = (new Model(true))
        ->table('baz');

        // No hay Schema
        Debug::dd($m->getSchema());
        
        Debug::dd($m->create([
            'id_baz' => 1800,
            'name' => 'BAZ',
			'cost' => '100',
        ]));
    }


    /*
        Se utiliza un modelo *sin* schema y sobre el cual no es posible hacer validaciones
    */
    function create_bar(){
        $m = (new Model(true))
        ->table('bar');

        // No hay Schema
        Debug::dd($m->getSchema());
        
        Debug::dd($m->create([
            'name' => 'ggg',
			'price' => '88.90',
        ]));
    }

    function create_bar1(){
        $m = DB::table('bar');
        $m->setValidator(new Validator());

        // SI hay schema
        Debug::dd($m->getSchema());
        
        Debug::dd($m->create([
            'name' => 'gggggggggg',
			'price' => '100',
        ]));
    }

    function get_products(){
        Debug::dd(DB::table('products')->get());
    }

    function get_products2(){
        Debug::dd(DB::table('products')->where(['size', '2L'])->get());
    }

    function create_p(){

        $name = '';
        for ($i=0;$i<20;$i++)
            $name .= chr(rand(97,122));

        $id = DB::table('products')->create([ 
            'name' => $name, 
            'description' => 'Esto es una prueba 77', 
            'size' => '100L',
            'cost' => 66,
            'belongs_to' => 90
        ]);   
        
        return $id;
    }

    function create_baz($id = null){

        $name = '';
        for ($i=0;$i<20;$i++)
            $name .= chr(rand(97,122));

        $data = [ 
            'name' => $name,
            'cost' => 100
        ];

        if ($id != null){
            $data['id'] = $id;
        }

        $id = DB::table('baz')->create($data);    

        Debug::dd($id, 'las_inserted_id');
    }

    
    // implementada y funcionando en register() 
    function transaction(){
        DB::beginTransaction();

        try {
            $name = '';
            for ($i=0;$i<20;$i++)
                $name .= chr(rand(97,122));

            $id = DB::table('products')->create([ 
                'name' => $name, 
                'description' => 'bla bla bla', 
                'size' => rand(1,5).'L',
                'cost' => rand(0,500),
                'belongs_to' => 90
            ]);   

            //throw new \Exception("AAA"); 

            DB::commit();

        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        } catch (\Throwable $e) {
            DB::rollback();            
        }            
    }

    // https://fideloper.com/laravel-database-transactions
    function transaction2(){
        DB::transaction(function(){
            $name = '';
            for ($i=0;$i<20;$i++)
                $name .= chr(rand(97,122));

            $id = DB::table('products')->create([ 
                'name' => $name, 
                'description' => 'Esto es una prueba', 
                'size' => rand(1,5).'L',
                'cost' => rand(0,500),
                'belongs_to' => 90
            ]);   

            throw new \Exception("AAA"); 
        });      
    }
    
    function output_mutator(){
        $rows = DB::table('users')
        ->registerOutputMutator('username', function($str){ return strtoupper($str); })
        ->get();

        Debug::dd($rows);
    }

    function output_mutator2(){
        $rows = DB::table('products')
        ->registerOutputMutator('size', function($str){ return strtolower($str); })
        ->groupBy(['size'])
        ->having(['AVG(cost)', 150, '>='])
        ->select(['size'])
        ->selectRaw('AVG(cost)')
        ->get();

        Debug::dd($rows);
    }

    /*
        El problema de los campos ocultos es que rompen los transformers
        usar when() en su lugar

        https://laravel.com/docs/5.5/eloquent-resources
    */
    function transform(){
        //$this->is_admin = true;


        $t = new \simplerest\transformers\UsersTransformer();

        $rows = DB::table('users')
        ->registerTransformer($t, $this)
        ->get();

        Debug::dd($rows);
    }

    function transform_and_output_mutator(){
        $t = new \simplerest\transformers\UsersTransformer();

        $rows = DB::table('users')
        ->registerOutputMutator('username', function($str){ return strtoupper($str); })
        ->registerTransformer($t)
        ->get();

        Debug::dd($rows);
    }

    function transform2(){
        $t = new \simplerest\transformers\ProductsTransformer();

        $rows = DB::table('products')
        ->where(['size'=>'2L'])
        ->registerTransformer($t)
        ->get();

        Debug::dd($rows);
    }


    function limit(){
        Debug::dd(DB::table('products')
        ->offset(20)
        ->select(['id', 'name', 'cost'])
        ->limit(10)
        ->get());
        
        Debug::dd(DB::getLog());

        Debug::dd(DB::table('products')->limit(10)->get());
        Debug::dd(DB::getLog());
    }

    function limit0(){
        Debug::dd(DB::table('products')
        ->offset(20)
        ->select(['id', 'name', 'cost'])
        ->limit(10)
        ->setPaginator(false)
        ->get());
        
        Debug::dd(DB::getLog());

        Debug::dd(DB::table('products')->limit(10)->get());
        Debug::dd(DB::getLog());
    }
    
    ///
    function limite(){
        DB::table('products')->offset(20)->limit(10)->get();
        Debug::dd(DB::getLog());

        DB::table('products')->limit(10)->get();
        Debug::dd(DB::getLog());
    }

    function distinct(){
        Debug::dd(DB::table('products')->distinct()->get(['size']));

        // Or
        Debug::dd(DB::table('products')->distinct(['size'])->get());

        // Or
        Debug::dd(DB::table('products')->select(['size'])->distinct()->get());
    }

    function distinct1(){
        Debug::dd(DB::table('products')->select(['size', 'cost'])->distinct()->get());
    }

    function distinct2(){
        Debug::dd(DB::table('users')->distinct()->get());
    }

    function distinct3(){
        Debug::dd(DB::table('products')->distinct()->get());
    }

    function pluck(){
        $names = DB::table('products')->pluck('size');

        foreach ($names as $name) {
            Debug::dd($name);
        }
    }

    function pluck2($uid) {
        $perms = DB::table('user_sp_permissions')
        ->assoc()
        ->where(['user_id' => $uid])
        ->join('sp_permissions', 'user_sp_permissions.sp_permission_id', '=', 'sp_permissions.id')
        ->pluck('name');

        Debug::dd($perms);
    }

    function get_product($id){       
        // Include deleted items
        Debug::dd(DB::table('products')->where(['id' => $id])->showDeleted()->get());
    }
    
    function exists(){
       
        Debug::dd(DB::table('products')->where(['belongs_to' => 103])->exists());
        //Debug::dd(DB::getLog());

        Debug::dd(DB::table('products')->where([ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ])->exists());
        //Debug::dd(DB::  getLog());
		
        Debug::dd(DB::table('users')->where(['username' => 'boctulus'])->exists());
        //Debug::dd(DB::  getLog());
    }
           
    function first(){
        Debug::dd(DB::table('products')->where([ 
            ['cost', 50, '>='],
            ['cost', 500, '<='],
            ['belongs_to',  90]
        ])->first(['name', 'size', 'cost'])); 
    }

    function value(){
		Debug::dd(DB::table('products')->where([ 
            ['cost', 5000, '>=']
        ])->value('name')); 
		
        Debug::dd(DB::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 500, '<='],
            ['belongs_to',  90]
        ])->value('name')); 
    }

    function oldest(){
        // oldest first
        Debug::dd(DB::table('products')->oldest()->get());
    }

    function newest(){
        // newest, first result
        Debug::dd(DB::table('products')->newest()->first());
    }
    
    // random or rand
    function random(){
        //Debug::dd(DB::table('products')->random()->get(['id', 'name']), 'ALL');
        Debug::dd(DB::table('products')->random()->select(['id', 'name'])->get(), 'ALL');

        Debug::dd(DB::table('products')->random()->limit(5)->get(['id', 'name']), 'N RESULTS');

        Debug::dd(DB::table('products')->random()->select(['id', 'name'])->first(), 'FIRST');
    }

    function count(){
        DB::setConnection('db1');

        $c = DB::table('products')
        ->where([ 'belongs_to'=> 90] )
        ->count();

        Debug::dd($c);
    }

    function count1(){
        $c = DB::table('products')
        //->assoc()
        ->where([ 'belongs_to'=> 90] )
        ->count('*', 'count');

        Debug::dd($c);
        Debug::dd(DB::getLog());
    }

    function count1b(){
        // SELECT COUNT(*) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL 

        $res =  DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->count();

        Debug::dd($res);
        Debug::dd(DB::getLog());
    } 

    function count2(){
        // SELECT COUNT(DISTINCT description) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL  

        $res = DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->distinct()
        ->count('description');

        Debug::dd($res);
        Debug::dd(DB::getLog());
    }

    function count2b(){
        // SELECT COUNT(DISTINCT description) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL  

        $res = DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->distinct()
        ->count('description', 'count');

        Debug::dd($res);
        Debug::dd(DB::getLog());
    }

    function count3(){
        $uid = 415;

        $count = (int) DB::table('user_roles')
		->where(['user_id' => $uid])->setFetchMode('COLUMN')
		->count();
		
        Debug::dd($count);
        Debug::dd(DB::getLog());
    }

    function avg(){
        // SELECT AVG(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        $res = DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->avg('cost', 'prom');

        Debug::dd($res);
    }

    function sum(){
        // SELECT SUM(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        $res = DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->sum('cost', 'suma');

        Debug::dd($res);
    }

    function min(){
        // SELECT MIN(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        $res = DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->min('cost', 'minimo');

        Debug::dd($res);
    }

    function max(){
        // SELECT MIN(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        $res =  DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->max('cost', 'maximo');

        Debug::dd($res);
    }

    /*
        select and addSelect
    */
    function select() {
        Debug::dd(DB::table('products')->random()->select(['id', 'name'])->addSelect('cost')->first());
    }

    /*
        RAW select

        pluck() no se puede usar con selectRaw() si posee un "as" pero la forma de lograr lo mismo
        es seteando el "fetch mode" en "COLUMN"

        Investigar como funciona el pluck() de Larvel
        https://stackoverflow.com/a/40964361/980631
    */
    function select2() {
        Debug::dd(DB::table('products')->setFetchMode('COLUMN')
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get());
    }

    function select3() {
        Debug::dd(DB::table('products')->setFetchMode('COLUMN')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get());
    }

    function select3a() {
        Debug::dd(DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get());
    }

    function select3b() {
        Debug::dd(DB::table('products')->setFetchMode('COLUMN')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get());
    }

    function select4() {
        Debug::dd(DB::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])
        ->addSelect('name', 'cost')
        ->get());
    }

    /*
        La ventaja de usar select() - por sobre usar get() - es que se ejecuta antes que count() permitiendo combinar selección de campos con COUNT() 

        SELECT size, COUNT(*) FROM products GROUP BY size
    */
    function select_group_count(){
        Debug::dd(DB::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])->count());
		Debug::dd(DB::getLog());
    }

    /*
        SELECT size, AVG(cost) FROM products GROUP BY size
    */
    function select_group_avg(){
        Debug::dd(DB::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])
        ->avg('cost'));    
    }

    function filter_products1(){
        Debug::dd(DB::table('products')->showDeleted()->where([ 
            ['size', '2L']
        ])->get());
    }
    
    function filter_products2(){
        Debug::dd(DB::table('products')
        ->where([ 
            ['name', ['Vodka', 'Wisky', 'Tekila','CocaCola']], // IN 
            ['locked', 0],
            ['belongs_to', 90]
        ])
        ->whereNotNull('description')
        ->get());
    }

    function testx(){
        $rows = DB::table('products')
        ->whereNotNull('name')
        ->get();

        Debug::dd($rows);
    }

    // SELECT * FROM products WHERE name IN ('CocaCola', 'PesiLoca') OR cost IN (100, 200)  OR cost >= 550 AND deleted_at IS NULL
    function filter_products3(){

        Debug::dd(DB::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca']], 
            ['cost', 550, '>='],
            ['cost', [100, 200]]
        ], 'OR')->get());    
    }

    function filter_products4(){    
        Debug::dd(DB::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca', 'Wisky', 'Vodka'], 'NOT IN']
        ])->get());
    }

    function filter_products5(){
        // implicit 'AND'
        Debug::dd(DB::table('products')->where([ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ])->get());        
    }

    function filter_products6(){
        Debug::dd(DB::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<=']
        ])->get());            
    }

    // WHERE IN
    function where1(){
        Debug::dd(DB::table('products')->where(['size', ['0.5L', '3L'], 'IN'])->get());
    }

    // WHERE IN
    function where2(){
        Debug::dd(DB::table('products')->where(['size', ['0.5L', '3L']])->get());
    }

    // WHERE IN
    function where3(){
        Debug::dd(DB::table('products')->whereIn('size', ['0.5L', '3L'])->get());
    }

    //WHERE NOT IN
    function where4(){
        Debug::dd(DB::table('products')->where(['size', ['0.5L', '3L'], 'NOT IN'])->get());
    }

    //WHERE NOT IN
    function where5(){
        Debug::dd(DB::table('products')->whereNotIn('size', ['0.5L', '3L'])->get());
    }

    // WHERE NULL
    function where6(){  
        Debug::dd(DB::table('products')->where(['workspace', null])->get());   
    }

    // WHERE NULL
    function where7(){  
        Debug::dd(DB::table('products')->whereNull('workspace')->get());
    }

    // WHERE NOT NULL
    function where8(){  
        Debug::dd(DB::table('products')->where(['workspace', null, 'IS NOT'])->get());   
    }

    // WHERE NOT NULL
    function where9(){  
        Debug::dd(DB::table('products')->whereNotNull('workspace')->get());
    }

    // WHERE BETWEEN
    function where10(){
        Debug::dd(DB::table('products')
        ->select(['name', 'cost'])
        ->whereBetween('cost', [100, 250])->get());
    }

    // WHERE BETWEEN
    function where11(){
        Debug::dd(DB::table('products')
        ->select(['name', 'cost'])
        ->whereNotBetween('cost', [100, 250])->get());
    }
    
    function where12(){
        Debug::dd(DB::table('products')
        ->find(103));
    }

    function where13(){
        Debug::dd(DB::table('products')
        ->where(['cost', 150])
        ->value('name'));
    }

    /*
        SELECT  name, cost, id FROM products WHERE belongs_to = '90' AND (cost >= 100 AND cost < 500) AND description IS NOT NULL
    */
    function where14(){
        Debug::dd(DB::table('products')->showDeleted()
        ->select(['name', 'cost', 'id'])
        ->where(['belongs_to', 90])
        ->where([ 
            ['cost', 100, '>='],
            ['cost', 500, '<']
        ])
        ->whereNotNull('description')
        ->get());
    }

    // SELECT  name, cost, id FROM products WHERE belongs_to = '90' AND (name IN ('CocaCola', 'PesiLoca')  OR cost >= 550 OR cost < 100) AND description IS NOT NULL
    function where_or(){
        Debug::dd(DB::table('products')->showDeleted()
        ->select(['name', 'cost', 'id'])
        ->where(['belongs_to', 90])
        ->where([ 
            ['name', ['CocaCola', 'PesiLoca']], 
            ['cost', 550, '>='],
            ['cost', 100, '<']
        ], 'OR')
        ->whereNotNull('description')
        ->get());
    }
    
    /* 
        A OR (B AND C)

        SELECT  name, cost, id FROM products WHERE (1 = 1)  AND belongs_to = ?  OR name IN ('CocaCola', 'PesiLoca') OR (cost <= ? AND cost >= ?)
    */
    function or_where(){
        Debug::dd(DB::table('products')->showDeleted()
        ->select(['name', 'cost', 'id'])
        ->where(['belongs_to', 90])
        ->orWhere(['name', ['CocaCola', 'PesiLoca']])
        ->orWhere([
            ['cost', 550, '<='],
            ['cost', 100, '>=']
        ])
        ->get());
    }
    
    // A OR (B AND C)
    function or_where2(){
        Debug::dd(DB::table('products')->showDeleted()
        ->select(['name', 'cost', 'id', 'description'])
        ->whereNotNull('description')
        ->orWhere([ 
                    ['cost', 100, '>='],
                    ['cost', 500, '<']
        ])        
        ->get());
    }

    /*
        Showing also deleted records

        SELECT  name, cost, id FROM products WHERE (belongs_to = '90' AND (name IN ('CocaCola', 'PesiLoca')  OR cost >= 550 OR cost < 100) AND description IS NOT NULL) AND deleted_at IS NULL OR  (cost >= 100 AND cost < 500)

    */
    function where_or2(){
        Debug::dd(DB::table('products')
        ->select(['id', 'name', 'cost', 'description'])
        ->where(['belongs_to', 90])
        ->where([ 
            ['name', ['CocaCola', 'PesiLoca']], 
            ['cost', 550, '>='],
            ['cost', 100, '<']
        ], 'OR')
        ->whereNotNull('description')
        ->get());
    }

    // SELECT * FROM users WHERE (email = 'nano@g.c' OR  username = 'nano') AND deleted_at IS NULL
    function or_where3(){
        $email = 'nano@g.c';
        $username = 'nano';

        $rows = DB::table('users')->assoc()->unhide(['password'])
            ->where([ 'email'=> $email, 
                      'username' => $username 
            ], 'OR') 
            ->setValidator((new Validator())->setRequired(false))  
            ->get();

        Debug::dd($rows);
    }

    // SELECT * FROM users WHERE (email = 'nano@g.c' OR  username = 'nano') AND deleted_at IS NULL
    function or_where3b(){
        $email = 'nano@g.c';
        $username = 'nano';

        $rows = DB::table('users')->assoc()
            ->where([ 'email'=> $email ]) 
            ->orWhere(['username' => $username ])
            ->setValidator((new Validator())->setRequired(false))  
            ->get();

        Debug::dd($rows);
    }


    // SELECT * FROM products WHERE ((cost < IF(size = "1L", 300, 100) AND size = '1L' ) AND belongs_to = 90) AND deleted_at IS NULL ORDER BY cost ASC
    function where_raw(){
        Debug::dd(DB::table('products')
        ->where(['belongs_to' => 90])
        ->whereRaw('cost < IF(size = "1L", ?, 100) AND size = ?', [300, '1L'])
        ->orderBy(['cost' => 'ASC'])
        ->get());
    }
   
    /*
        SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname IS NOT NULL);
    */
    function where_raw2(){
        Debug::dd(DB::table('products')->showDeleted()
        ->whereRaw('EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?  )', ['AB'])
        ->get());
    }


    /*
        WHERE EXISTS

        SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname IS NOT NULL);
    */
    function where_exists(){
        Debug::dd(DB::table('products')->showDeleted()
        ->whereExists('(SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?)', ['AB'])
        ->get());
    }

    /*
        SELECT * FROM products WHERE 1 = 1 AND deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
    */
    function order(){    
        Debug::dd(DB::table('products')->orderBy(['cost'=>'ASC', 'id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::dd(DB::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::dd(DB::table('products')->orderBy(['cost'=>'ASC'])->take(4)->offset(1)->get(null, ['id'=>'DESC']));

        Debug::dd(DB::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::dd(DB::table('products')->take(4)->offset(1)->get(null, ['cost'=>'ASC', 'id'=>'DESC']));
    }

    /*
        RAW
        
        SELECT * FROM products WHERE 1 = 1 AND deleted_at IS NULL ORDER BY locked + active ASC
    */
    function order2(){
        Debug::dd(DB::table('products')->orderByRaw('locked * active DESC')->get()); 
    }

    function grouping(){
        Debug::dd(DB::table('products')->where([ 
            ['cost', 100, '>=']
        ])->orderBy(['size' => 'DESC'])
        ->groupBy(['size'])
        ->select(['size'])
        //->take(5)
        //->offset(5)
        ->avg('cost'));
    }

    function where(){        

        // Ok
        Debug::dd(DB::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to',  90]
        ])->get());  
        

        /*    
        // No es posible mezclar arrays asociativos y no-asociativos 
        Debug::dd(DB::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to' =>  90]
        ])->get());
        */        

        // Ok
        Debug::dd(DB::table('products')
        ->where([ 
                ['cost', 150, '>='],
                ['cost', 270, '<=']            
            ])
        ->where(['belongs_to' =>  90])->get());         
    }
        
    function having(){  
        Debug::dd(DB::table('products')
			//->dontExec()
            ->groupBy(['size'])
            ->having(['AVG(cost)', 150, '>='])
            ->select(['size'])
			->selectRaw('AVG(cost)')
			->get());
			
		Debug::dd(DB::getLog()); 
    }  

	/*
		Array
		(
			[0] => stdClass Object
				(
					[c] => 3
					[name] => Agua
				)

			[1] => stdClass Object
				(
					[c] => 5
					[name] => Vodka
				)

		)
		
		SELECT COUNT(name) as c, name FROM products WHERE deleted_at IS NULL GROUP BY name HAVING c >= 3
	*/	
	function having0(){  
        Debug::dd(DB::table('products')
			//->dontExec()
            ->groupBy(['name'])
            ->having(['c', 3, '>='])
            ->select(['name'])
			->selectRaw('COUNT(name) as c')
			->get());
			
		Debug::dd(DB::getLog()); 
    }  
	
	/*
		Array
		(
			[0] => stdClass Object
				(
					[c] => 5
					[name] => Agua 
				)

			[1] => stdClass Object
				(
					[c] => 3
					[name] => Ron
				)

			[2] => stdClass Object
				(
					[c] => 9
					[name] => Vodka
				)

		)

		SELECT COUNT(name) as c, name FROM products GROUP BY name HAVING c >= 3
	*/
	function havingx(){  
        Debug::dd(DB::table('products')->showDeleted()
			//->dontExec()
            ->groupBy(['name'])
            ->having(['c', 3, '>='])
            ->select(['name'])
			->selectRaw('COUNT(name) as c')
			->get());
			
		Debug::dd(DB::getLog()); 
    }  

    /*       
        En caso de tener múltiples condiciones se debe enviar un 
        array de arrays pero para una sola condición basta con enviar un simple array

        Cuando la condición es por igualdad (ejemplo: HAVING cost = 100), no es necesario
        enviar el operador "=" ya que es implícito y en este caso se puede usar un array asociativo:

            ->having(['cost' => 100])

        en vez de

            ->having(['cost', 100])

        En el caso de múltiples condiciones estas se concatenan implícitamente con "AND" excepto 
        se espcifique "OR" como segundo parámetro de having()    
    */     
	
	/*
		SELECT cost, size FROM products WHERE deleted_at IS NULL GROUP BY cost,size HAVING cost = 100
	*/
    function having1(){        
        Debug::dd(DB::table('products')
            ->groupBy(['cost', 'size'])
            ->having(['cost', 100])
            ->get(['cost', 'size']));
		
		Debug::dd(DB::getLog()); 
    }    
	
	// SELECT cost, size FROM products GROUP BY cost,size HAVING cost = 100
	function having1b(){        
        Debug::dd(DB::table('products')->showDeleted()
            ->groupBy(['cost', 'size'])
            ->having(['cost', 100])
            ->get(['cost', 'size']));
		
		Debug::dd(DB::getLog()); 
    }   
	
    /*
        HAVING ... OR ... OR ...

        SELECT  cost, size, belongs_to FROM products WHERE deleted_at IS NULL GROUP BY cost,size,belongs_to HAVING belongs_to = 90 AND (cost >= 100 OR size = '1L') ORDER BY size DESC
    */
    function having2(){
        Debug::dd(DB::table('products')
            ->groupBy(['cost', 'size', 'belongs_to'])
            ->having(['belongs_to', 90])
            ->having([  
                        ['cost', 100, '>='],
                        ['size' => '1L'] ], 
            'OR')
            ->orderBy(['size' => 'DESC'])
            ->get(['cost', 'size', 'belongs_to'])); 
    }

    /*
        OR HAVING
    
        SELECT  cost, size, belongs_to FROM products WHERE deleted_at IS NULL GROUP BY cost,size,belongs_to HAVING  belongs_to = 90 OR  cost >= 100 OR  size = '1L'  ORDER BY size DESC
    */
    function having2b(){
        Debug::dd(DB::table('products')
            ->groupBy(['cost', 'size', 'belongs_to'])
            ->having(['belongs_to', 90])
            ->orHaving(['cost', 100, '>='])
            ->orHaving(['size' => '1L'])
            ->orderBy(['size' => 'DESC'])
            ->get(['cost', 'size', 'belongs_to'])); 
    }

    /*
        SELECT  cost, size, belongs_to FROM products WHERE deleted_at IS NULL GROUP BY cost,size,belongs_to HAVING  belongs_to = 90 OR  (cost >= 100 AND size = '1L')  ORDER BY size DESC
    */
    function having2c(){
        Debug::dd(DB::table('products')
            ->groupBy(['cost', 'size', 'belongs_to'])
            ->having(['belongs_to', 90])
            ->orHaving([  
                        ['cost', 100, '>='],
                        ['size' => '1L'] ] 
            )
            ->orderBy(['size' => 'DESC'])
            ->get(['cost', 'size', 'belongs_to'])); 
    }

    /*
        RAW HAVING
    */
    function having3(){
        Debug::dd(DB::table('products')
            ->selectRaw('SUM(cost) as total_cost')
            ->where(['size', '1L'])
            ->groupBy(['belongs_to']) 
            ->havingRaw('SUM(cost) > ?', [500])
            ->limit(3)
            ->offset(1)
            ->get());
    }

    function joins(){
        $o = DB::table('other_permissions', 'op');
        $rows =   $o->join('folders', 'op.folder_id', '=',  'folders.id')
                    ->join('users', 'folders.belongs_to', '=', 'users.id')
                    ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->where([
                        ['guest', 1],
                        ['table', 'products'],
                        ['r', 1]
                    ])
                    ->orderByRaw('users.id DESC')
                    ->get();  
        
        Debug::dd($rows);
    }
 
    function get_nulls(){    
        // Get products where workspace IS NULL
        Debug::dd(DB::table('products')->where(['workspace', null])->get());   
   
        // Or
        Debug::dd(DB::table('products')->whereNull('workspace')->get());
    }

    /*
        Debug without exec the query
    */
    function dontexec(){
        DB::table('products')
        ->dontExec() 
        ->where([ 
                ['cost', 150, '>='],
                ['cost', 270, '<=']            
            ])
        ->where(['belongs_to' =>  90])->get(); 
        
        Debug::dd(DB::getLog()); 
    }

    /*
        Pretty response 
    */
    function get_users(){
        $array = DB::table('users')->orderBy(['id'=>'DESC'])->get();

        echo '<pre>';
        Factory::response()->setPretty(true)->send($array);
        echo '</pre>';
    }

    function get_user($id){
        $u = DB::table('users');
        $u->unhide(['password']);
        $u->hide(['username', 'confirmed_email', 'firstname','lastname', 'deleted_at', 'belongs_to']);
        
        Debug::dd($u->where(['id'=>$id])->get());
    }

    function del_user($id){
        $u = DB::table('users');
        $ok = (bool) $u->where(['id' => $id])->setSoftDelete(false)->delete();
        
        Debug::dd($ok);
    }

 
    function update_user($id) {
        $u = DB::table('users');

        $count = $u->where(['firstname' => 'HHH', 'lastname' => 'AAA', 'id' => 17])->update(['firstname'=>'Nico', 'lastname'=>'Buzzi', 'belongs_to' => 17]);
        
        Debug::dd($count);
    }

    function update_user2() 
    {
        $firstname = '';
        for ($i=0;$i<20;$i++)
            $firstname .= chr(rand(97,122));

        $lastname = strtoupper($firstname);    

        $u = DB::table('users');

        $ok = $u->where([ [ 'email', 'nano@'], ['deleted_at', NULL] ])
        ->update([ 
                    'firstname' => $firstname, 
                    'lastname' => $lastname
        ]);
        
        Debug::dd($ok);
    }

    function update_users() {
        $u = DB::table('users');
        $count = $u->where([ ['lastname', ['AAA', 'Buzzi']] ])->update(['firstname'=>'Nicos']);
        
        Debug::dd($count);
    }

    function create_user($email, $password, $firstname, $lastname)
     {        
        for ($i=0;$i<20;$i++)
            $email = chr(rand(97,122)) . $email;
        
        $u = DB::table('users');
        //$u->fill(['email']);
        //$u->unfill(['password']);
        $id = $u->create(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname]);
        
        Debug::dd($id);
    }

    function fillables(){
        $m = DB::table('files');
        $affected = $m->where(['id' => 240])->update([
            "filename_as_stored" => "xxxxxxxxxxxxxxxxx.jpg"
        ]);

        Debug::dd($affected, 'Affected:');

        // Show result
        $rows = DB::table('files')->where(['id' => 240])->get();
        Debug::dd($rows);
    }

    function update_products() {
        $p = DB::table('products');
        $count = $p->where([['cost', 100, '<'], ['belongs_to', 90]])->update(['description' => 'x_x']);
        
        Debug::dd($count);
    }

    function respuesta(){
        Factory::response()->sendError('Acceso no autorizado', 401, 'Header vacio');
    }
   
      // ok
    function sender(){
        Debug::dd(Utils::send_mail('boctulus@gmail.com', 'Pablo ZZ', 'Pruebita', 'Hola!<p/>Esto es una <b>prueba</b><p/>Chau'));     
    }

    function validation_test(){
        $rules = [
            'nombre' => ['type' => 'alpha_spaces_utf8', 'min'=>3, 'max'=>40],
            'username' => ['type' => 'alpha_dash', 'min'=> 3, 'max' => '15'],
            'rol' => ['type' => 'int', 'not_in' => [2, 4, 5]],
            'poder' => ['not_between' => [4,7] ],
            'edad' => ['between' => [18, 100]],
            'magia' => [ 'in' => [3,21,81] ],
            'active' => ['type' => 'bool', 'messages' => [ 'type' => 'Value should be 0 or 1'] ]
        ];
        
        $data = [
            'nombre' => 'Juan Español',
            'username' => 'juan_el_mejor',
            'rol' => 5,
            'poder' => 6,
            'edad' => 101,
            'magia' => 22,
            'active' => 3
        ];

        $v = new Validator();
        Debug::dd($v->validate($rules,$data));
    }

    function validacion(){
        $u = DB::table('users');
        Debug::dd($u->where(['username' => 'nano_'])->get());
    }

    function validacion1(){
        $u = DB::table('users')->setValidator(new Validator());
        $affected = $u->where(['email' => 'nano@'])->update(['firstname' => 'NA']);
    }

    function validacion2(){
        $u = DB::table('users')->setValidator(new Validator());
        $affected = $u->where(['email' => 'nano@'])->update(['firstname' => 'NA']);
    }

    function validacion3(){
        $p = DB::table('products')->setValidator(new Validator());
        $rows = $p->where(['cost' => '100X', 'belongs_to' => 90])->get();

        Debug::dd($rows);
    }

    function validacion4(){
        $p = DB::table('products')->setValidator(new Validator());
        $affected = $p->where(['cost' => '100X', 'belongs_to' => 90])->delete();

        Debug::dd($affected);
    }
  
    /*
        Intento #1 de sub-consultas en el WHERE

        SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL);

    */
    function sub(){
        $st = DB::table('products')->showDeleted()
        ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
        ->whereRaw('belongs_to IN (SELECT id FROM users WHERE password IS NULL)')
        ->get();

        Debug::dd(DB::getLog());  
        Debug::dd($st);         
    }

    /*
        Intento #2 de sub-consultas en el WHERE

        SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL);

    */
    function sub2(){
        $sub = DB::table('users')
        ->select(['id'])
        ->whereRaw('password IS NULL');

        $st = DB::table('products')->showDeleted()
        ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
        ->whereRaw("belongs_to IN ({$sub->toSql()})")
        ->get();

        Debug::dd(DB::getLog());
        Debug::dd($st);            
    }

    /*
        Subconsultas en el WHERE --ok
    */
    function sub3(){
        $sub = DB::table('users')->showDeleted()
        ->select(['id'])
        ->whereRaw('confirmed_email = 1')
        ->where(['password', 100, '<']);

        $res = DB::table('products')->showDeleted()
        ->mergeBindings($sub)
        ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
        ->where(['size', '1L'])
        ->whereRaw("belongs_to IN ({$sub->toSql()})")
        ->get();

        Debug::dd(DB::getLog());
        Debug::dd($res);    
    }

    /*
        SELECT  id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT  users.id FROM users  INNER JOIN user_roles ON users.id=user_roles.user_id WHERE confirmed_email = 1  AND password < 100 AND role_id = 2  )  AND size = '1L' ORDER BY id DESC

    */
    function sub3b(){
        $sub = DB::table('users')->showDeleted()
        ->selectRaw('users.id')
        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->whereRaw('confirmed_email = 1')
        ->where(['password', 100, '<'])
        ->where(['role_id', 2]);

        $res = DB::table('products')->showDeleted()
        ->mergeBindings($sub)
        ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
        ->where(['size', '1L'])
        ->whereRaw("belongs_to IN ({$sub->toSql()})")
        ->orderBy(['id' => 'desc'])
        ->get();

        Debug::dd(DB::getLog());  
        Debug::dd($res);    
    }

    function sub3c(){
        $sub = DB::table('users')->showDeleted()
        ->selectRaw('users.id')
        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
        ->whereRaw('confirmed_email = 1')
        ->where(['password', 100, '<'])
        ->where(['role_id', 3]);

        $res = DB::table('products')->showDeleted()
        ->mergeBindings($sub)
        ->select(['size'])
        ->whereRaw("belongs_to IN ({$sub->toSql()})")
        ->groupBy(['size'])
        ->avg('cost');

        Debug::dd($res);    
    }


    /*
        RAW select

    */

    function sub4(){
        // SELECT COUNT(*) FROM (SELECT  name, size FROM products  GROUP BY size ) as sub 
        
        try {
            $sub = DB::table('products')
            ->select(['name', 'size'])
            ->groupBy(['size']);

            $m = new Model(true);
            $res = $m->fromRaw("({$sub->toSql()}) as sub")
            ->count();

            Debug::dd($sub->toSql(), 'toSql()');
            Debug::dd($m->getLastPrecompiledQuery(), 'getLastPrecompiledQuery()');
            Debug::dd(DB::getLog(), 'getLog()');   
            Debug::dd($res, 'count');  

        } catch (\Exception $e){
            Debug::dd($e->getMessage());
            Debug::dd($m->dd());
        }
    }

    function sub4a(){
        try {
            $sub = DB::table('products')
            ->select(['id', 'name', 'size'])
            ->where(['cost', 150, '>=']);
        
            $m = new Model(true);    
            $res = $m->fromRaw("({$sub->toSql()}) as sub")
            ->mergeBindings($sub)
            ->count();
      
            Debug::dd($sub->toSql(), 'toSql()');
            Debug::dd($m->getLastPrecompiledQuery(), 'getLastPrecompiledQuery()');
            Debug::dd(DB::getLog(), 'getLog()');   
            Debug::dd($res, 'count'); 

        } catch (\Exception $e){
            Debug::dd($e->getMessage());
            Debug::dd($m->dd());
        }    
    }


    function sub4b(){
        $sub = DB::table('products')->showDeleted()
        ->select(['size'])
        ->groupBy(['size']);

        $m = new Model(true);
        $res = $m->fromRaw("({$sub->toSql()}) as sub")->count();

        Debug::dd($res);    
    }

    /*
        SELECT  COUNT(*) FROM (SELECT  size FROM products WHERE belongs_to = 90 GROUP BY size ) as sub WHERE 1 = 1
    */
    function sub4c(){
        $sub = DB::table('products')->showDeleted()
        ->select(['size'])
        ->where(['belongs_to', 90])
        ->groupBy(['size']);

        $main = new \simplerest\core\Model(true);
        $res = $main
        ->fromRaw("({$sub->toSql()}) as sub")
        ->mergeBindings($sub)
        ->count();

        Debug::dd($res); 
        Debug::dd($main->getLastPrecompiledQuery());   
    }

    /*
        FROM RAW

        SELECT  COUNT(*) FROM (SELECT  size FROM products WHERE belongs_to = 90 GROUP BY size ) as sub WHERE 1 = 1
    */
    function sub4d(){
        $sub = DB::table('products')->showDeleted()
        ->select(['size'])
        ->where(['belongs_to', 90])
        ->groupBy(['size']);

        $res = DB::table("({$sub->toSql()}) as sub")
        ->mergeBindings($sub)
        ->count();

        Debug::dd($res);    
    }
    
    /*
        Subconsulta (rudimentaria) en el SELECT
    */
    function sub5(){
        $m = DB::table('products')->showDeleted()
        ->select(['name', 'cost'])
        ->selectRaw('cost - (SELECT MAX(cost) FROM products) as diferencia')
        ->where(['belongs_to', 90]);

        $res = $m->get();

        Debug::dd($res);
        Debug::dd($m->getLastPrecompiledQuery()); 
        Debug::dd(DB::getLog()); 
        
    }

    /*
        UNION

        SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 90 UNION SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 4 ORDER by id ASC LIMIT 5;
    */
    function union(){
        $uno = DB::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 90]);

        $dos = DB::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 4])
        ->union($uno)
        ->orderBy(['id' => 'ASC'])
        ->limit(5)
        ->get();

        Debug::dd($dos);
    }

    function union2(){
        $uno = DB::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 90]);

        $m2  = DB::table('products')->showDeleted();
        $dos = $m2
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 4])
        ->where(['cost', 200, '>='])
        ->union($uno)
        ->orderBy(['id' => 'ASC'])
        ->get();

        //Debug::dd(DB::getLog());
        //Debug::dd($m2->getLastPrecompiledQuery());
        //Debug::dd($dos);
    }

    /*
        UNION ALL
    */
    function union_all(){
        $uno = DB::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 90]);

        $dos = DB::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['cost', 200, '>='])
        ->unionAll($uno)
        ->orderBy(['id' => 'ASC'])
        ->limit(5)
        ->get();

        Debug::dd($dos);
    }
       
    function insert_messages() {
        function get_words($sentence, $count = 10) {
            preg_match("/(?:\w+(?:\W+|$)){0,$count}/", $sentence, $matches);
            return $matches[0];
        }

        $m = DB::table('messages');

        for ($i=0; $i<1500; $i++){

            $name = '';
            for ($i=0;$i<10;$i++){
                $name .= chr(rand(97,122));
            }   

            $email = '';
            for ($i=0;$i<20;$i++){
                $email .= chr(rand(97,122));
            }   

            $email .= '@gmail.com';

            $title = file_get_contents('http://loripsum.net/api/1/short/plaintext/short');
            $title = get_words($title, 10);

            $content = file_get_contents('http://loripsum.net/api/1/long/plaintext/short');

            $phone = '0000000000';

            $m->create([ 
                        'name' => $name, 
                        'email' => $email,
                        'phone' => $phone,
                        'subject' => $title,
                        'content' => $content
            ]);

        }        
    }

    // utiliza FPM, sin probar
    function some_test() {
       ignore_user_abort(true);
       fastcgi_finish_request();

       echo json_encode(['data' => 'Proceso terminado']);
       header('Connection: close');

       sleep(10);
       file_put_contents('output.txt', date('l jS \of F Y h:i:s A')."\n", FILE_APPEND);
    }

    function json(){
        $id = DB::table('collections')->create([
            'entity' => 'messages',
            'refs' => json_encode([195,196]),
            'belongs_to' => 332
        ]);

        Factory::response()->sendJson($id);
    }

    function test_get(){
        Debug::dd(DB::table('products')->first(), 'FIRST'); 
        Debug::dd(DB::getLog(), 'QUERY');
    }

    function test_get_raw(){
        $raw_sql = 'SELECT * FROM baz';

        $conn = DB::getConnection();
        
        $st = $conn->prepare($raw_sql);
        $st->execute();

        $result = $st->fetch(\PDO::FETCH_ASSOC);

        // additional casting
        $result['cost'] = (float) $result['cost'];
        
        echo '<pre>';
        var_export($result);
        echo '</pre>';
    }

    function test_raw(){
        $res = DB::select('SELECT * FROM baz');
        Debug::dd($res);
    }

    function get_role_permissions(){
        $acl = Factory::acl();

        Debug::dd($acl->hasResourcePermission('show_all', ['guest'], 'products'));
        //var_export($acl->getRolePermissions());
    }

    function boom(){
        throw new \Exception('BOOOOM');
    }

    function ops(){
        $this->boom();
    }

    function hi($name = null){
        return 'hi ' . $name;
    }

  
    function xxx(){ 
        Debug::dd(Validator::isType('8', 'str'));
    }

    function speed(){
        $rules = [
            'nombre' => ['type' => 'alpha_spaces_utf8', 'min'=>3, 'max'=>40],
            'username' => ['type' => 'alpha_dash', 'min'=> 3, 'max' => '15'],
            'rol' => ['type' => 'int', 'not_in' => [2, 4, 5]],
            'poder' => ['not_between' => [4,7] ],
            'edad' => ['between' => [18, 100]],
            'magia' => [ 'in' => [3,21,81] ],
            'active' => ['type' => 'bool', 'messages' => [ 'type' => 'Value should be 0 or 1'] ]
        ];
        
        $data = [
            'nombre' => 'Juan Español',
            'username' => 'juan_el_mejor',
            'rol' => 5,
            'poder' => 6,
            'edad' => 101,
            'magia' => 22,
            'active' => 3
        ];

        Time::setUnit('MILI');
        $t1 = Time::exec(function() use($data, $rules){ 
            Factory::validador()->validate($rules,$data);
        }, 100); 
        
        Debug::dd("Time: $t1 ms");
    }

    function get_con(){
        DB::setConnection('db2');       
        $conn = DB::getConnection();

        $m = new \simplerest\models\ProductsModel($conn);
    }

    /*
        MySql: show status where `variable_name` = 'Threads_connected
        MySql: show processlist;
    */
    function test_active_connnections(){
        Debug::dd(DB::countConnections(), 'Number of active connections');

        DB::setConnection('db2');  
        Debug::dd(DB::table('users')->count(), 'Users DB2:'); 

        DB::setConnection('db1');  
        Debug::dd(DB::table('users')->count(), 'Users DB1');

        DB::setConnection('db2');  
        Debug::dd(DB::table('users')->first(), 'Users DB2:');

        Debug::dd(DB::countConnections(), 'Number of active connections'); // 2 y no 3 ;)

        DB::closeConnection();
        Debug::dd(DB::countConnections(), 'Number of active connections'); // 1

        DB::closeAllConnections();
        Debug::dd(DB::countConnections(), 'Number of active connections'); // 0
    }

    function read_table(){
        $tb = 'products';

        $fields = DB::select("SHOW COLUMNS FROM $tb");
        
        $field_names = [];
        $nullables = [];

        foreach ($fields as $field){
            $field_names[] = $field['Field'];
            if ($field['Null']  == 'YES') { $nullables[] = $field['Field']; }
            if ($field['Extra'] == 'auto_increment') { $not_fillable[] = $field['Field']; }
        }

        Debug::dd($field_names);
    }

    function zzz(){
        $arr = ['el', 'dia', 'que', 'me', 'quieras'];
        $arr = array_map(function($x){ return "'$x'"; }, $arr);
        
        Debug::dd($arr);
        
        //echo implode('-', $arr);
    }

    function speed2(){

        Time::setUnit('MILI');
        //Time::noOutput();
        
        $conn = DB::getConnection();
        $t = Time::exec(function() use ($conn){         
            $sql = "INSERT INTO `baz2` (`name`, `cost`) VALUES ('hhh', '789')";
            $conn->exec($sql);
        }, 1);  
        Debug::dd("Time: $t ms");    

        exit;

        $m = (new Model(true))
        ->table('baz2');
        $t = Time::exec(function() use ($m){             
            //$m->setValidator(new Validator());
            //$m->dontExec();

            $id = $m->create([
                'name' => 'BAZ',
                'cost' => '100',
            ]);

        }, 1);  
        Debug::dd("Time: $t ms");
        Debug::dd($m->getLog());

        /*
        Time::setUnit('MILI');
        //Time::noOutput();

        $this->model_name  = null;
        $this->model_table = 'users';

        $t = Time::exec(function(){ 
            
            $id = DB::table('collections')->create([
                'entity' => 'messages',
                'refs' => json_encode([195,196]),
                'belongs_to' => 332
            ]);

        }, 1);       
        Debug::dd("Time: $t ms");
        */
    }


    function speed_show()
    {
        Time::setUnit('MILI');

        $m = (new Model(true))
            ->table('bar')  
            ->where(['uuid', '0fefc2b1-f0d3-47aa-a875-5dbca85855f9'])
            ->select(['uuid', 'price']);

        //Debug::dd($m->dd());
        //exit;     

        $t = Time::exec(function() use($m) {
            $row = $m->get();
        }, 1);

        //Debug::dd("Time: $t ms");
        Files::logger("Time(show) : $t ms");
    }

    function speed_list()
    {
        Time::setUnit('MILI');

        $m = (new Model(true))
            ->table('bar')  
            ->select(['uuid', 'price'])
            ->take(10);

        //Debug::dd($m->dd());
        //exit;         

        $t = Time::exec(function() use($m) {
            $row = $m->get();
        }, 1);

        //Debug::dd("Time: $t ms");
        Files::logger("Time(list) : $t ms");
    }

    function get_bulk(){
        $t1a = [];
        $t2a = [];

        Time::setUnit('MILI');

        $m1 = (new Model(true))
            ->table('bar')  
            ->select(['uuid', 'price'])
            ->take(10);

        $m2 = (new Model(true))
            ->table('bar')  
            ->where(['uuid', '0fefc2b1-f0d3-47aa-a875-5dbca85855f9'])
            ->select(['uuid', 'price']);    

        //Debug::dd($m->dd());
        //exit;         

        $m3 = DB::select("SELECT AVG(price) FROM bar;");

        for ($i=0; $i<4; $i++){
            $t1a[] = Time::exec(function() use($m1) {
                $m1->get();
            }, 500);

            $t2a[] = Time::exec(function() use($m2) {
                $m2->get();
            }, 500);
        }    
            
        foreach ($t1a as $t1){
            Files::logger("Time(list) : $t1 ms");
        }

        foreach ($t2a as $t2){
            Files::logger("Time(show) : $t2 ms");;
        }    
    }

    function create(){
        $m = (new BarModel(true));

        $name = '    ';
        for ($i=0;$i<46;$i++)
            $name .= chr(rand(97,122));

        $name = str_shuffle($name);

        $email = '@';
        $cnt = rand(10,78);
        for ($i=0;$i<$cnt;$i++)
            $email .= chr(rand(97,122));    

        $email =  chr(rand(97,122)) . str_shuffle($email);

        $data = [
            'name' => $name,
            'price' => rand(5,999) . '.' . rand(0,99),
            'email' => $email,
            'belongs_to' => 1
        ];

        $id = $m->create($data);
        
        //Debug::dd($data, 'DATA');
        //Debug::dd($id, 'ID');
    }


    function create_bulk(){
        for ($i=0; $i<10000; $i++){
            $this->create();
            usleep((450 + rand(50, 150)) * 1000);
        }
    }


    /*

        https://www.w3resource.com/mysql/mysql-data-types.php
        https://manuales.guebs.com/mysql-5.0/spatial-extensions.html

    */
    function create_table()
    {       
        $sc = new Schema('facturas');

        $sc->setEngine('InnoDB');
        $sc->setCharset('utf8');
        $sc->setCollation('utf8_general_ci');

        $sc->serial('id')->pri();
        $sc->int('edad')->unsigned();
        $sc->varchar('firstname');
        $sc->varchar('lastname')->nullable()->charset('utf8')->collation('utf8_unicode_ci');
        $sc->varchar('username')->unique();
        $sc->varchar('password', 128);
        $sc->char('password_char');
        $sc->varbinary('texto_vb', 300);

        // BLOB and TEXT columns cannot have DEFAULT values.
        $sc->text('texto');
        $sc->tinytext('texto_tiny');
        $sc->mediumtext('texto_md');
        $sc->longtext('texto_long');
        $sc->blob('codigo');
        $sc->tinyblob('blob_tiny');
        $sc->mediumblob('blob_md');
        $sc->longblob('blob_long');
        $sc->binary('bb', 255);
        $sc->json('json_str');

        
        $sc->int('karma')->default(100);
        $sc->int('code')->zeroFill();
        $sc->bigint('big_num');
        $sc->bigint('ubig')->unsigned();
        $sc->mediumint('medium');
        $sc->smallint('small');
        $sc->tinyint('tiny');
        $sc->decimal('saldo');
        $sc->float('flotante');
        $sc->double('doble_p');
        $sc->real('num_real');

        $sc->bit('some_bits', 3)->index();
        $sc->boolean('active')->default(1);
        $sc->boolean('paused')->default(true);

        $sc->set('flavors', ['strawberry', 'vanilla']);
        $sc->enum('role', ['admin', 'normal']);


        /*
            The major difference between DATETIME and TIMESTAMP is that TIMESTAMP values are converted from the current time zone to UTC while storing, and converted back from UTC to the current time zone when accessd. The datetime data type value is unchanged.
        */

        $sc->time('hora');
        $sc->year('birth_year');
        $sc->date('fecha')->first();
        $sc->datetime('vencimiento')->nullable()->after('num_real');
        $sc->timestamp('ts')->currentTimestamp()->comment('some comment')->first(); // solo un first


        $sc->softDeletes(); // agrega DATETIME deleted_at 
        $sc->datetimes();  // agrega DATETIME(s) no-nullables created_at y deleted_at

        $sc->integer('id')->auto()->unsigned()->pri();
        $sc->varchar('correo')->unique();

        $sc->foreign('factura_id')->references('id')->on('facturas')->onDelete('no action');
        $sc->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');

        //Debug::dd($sc->getSchema(), 'SCHEMA');
        /////exit;

        $sc->create();
    }    


    function alter_table()
    {       
        $sc = new Schema('facturas');

        //Debug::dd($sc->getSchema());

        $res = $sc
        ->timestamp('vencimiento')
        ->varchar('lastname', 50)->collate('utf8_esperanto_ci')
        ->varchar('username', 50)
        ->column('ts')->nullable()
        ->field('deleted_at')->nullable()
        //->field('correo')->unique()
        
        /*
        echo $sc->renameColumn('codigo', 'binario');
        echo $sc->renameIndex('id', 'user_id');
        echo $sc->dropColumn('geo');
        echo $sc->dropIndex('bit');
        echo $sc->dropPrimary('bit');
        echo $sc->dropTable();
        */
        
        ->change();
    }

}