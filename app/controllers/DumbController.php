<?php

namespace simplerest\controllers;

use simplerest\core\Controller;
use simplerest\core\Request;
use simplerest\libs\Factory;
use simplerest\libs\Debug;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\models\ProductsModel;
use simplerest\models\UserRolesModel;
use PHPMailer\PHPMailer\PHPMailer;
use simplerest\libs\Utils;
use simplerest\libs\Validator;


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

    function create_p(){

        $name = '';
        for ($i=0;$i<20;$i++)
            $name .= chr(rand(97,122));

        $id = Database::table('products')->create([ 'name' => $name, 
                                                    'description' => 'Esto es una prueba', 
                                                    'size' => '1L',
                                                    'cost' => 66,
                                                    'belongs_to' => 90
        ]);    
    }

    function get_products(){
        Debug::dd(Database::table('products')->get());
        //Debug::dd(Database::table('products')->setFetchMode('ASSOC')->get());
    }

    function limit(){
        Debug::dd(Database::table('products')->offset(20)->limit(10)->get());
        Debug::dd(Database::getQueryLog());

        Debug::dd(Database::table('products')->limit(10)->get());
        Debug::dd(Database::getQueryLog());
    }
    
    function limite(){
        Database::table('products')->offset(20)->limit(10)->get();
        Debug::dd(Database::getQueryLog());

        Database::table('products')->limit(10)->get();
        Debug::dd(Database::getQueryLog());
    }

    function cuenta(){
        Database::table('users')
        ->where([ 'belongs_to'=> 160] )
        ->count();

        //Debug::dd(Database::getQueryLog());

        //
        /*
        $sub = Database::table('products')->showDeleted()
        ->select(['size'])
        ->groupBy(['size']);
    
        $conn = Database::getConnection();
    
        $m = new \simplerest\core\Model($conn);
        $res = $m->fromRaw("({$sub->toSql()}) as sub")->count();
    
        Debug::dd(Database::getQueryLog());     
        */

        // SELECT COUNT(*) FROM (SELECT  name, size FROM products  GROUP BY size ) as sub 
        $sub = Database::table('products')
        ->select(['name', 'size'])
        ->groupBy(['size']);
    
        $conn = Database::getConnection();
    
        $m = new \simplerest\core\Model($conn);
        $res = $m->fromRaw("({$sub->toSql()}) as sub")->count();
    
        //Debug::dd($sub->toSql());
        Debug::dd($m->getLastPrecompiledQuery());
        Debug::dd(Database::getQueryLog());     
    }

    function distinct(){
        Debug::dd(Database::table('products')->distinct()->get(['size']));

        // Or
        Debug::dd(Database::table('products')->distinct(['size'])->get());

        // Or
        Debug::dd(Database::table('products')->select(['size'])->distinct()->get());
    }

    function distinct1(){
        Debug::dd(Database::table('products')->select(['size', 'cost'])->distinct()->get());
    }

    function distinct2(){
        Debug::dd(Database::table('users')->distinct()->get());
    }

    function distinct3(){
        Debug::dd(Database::table('products')->distinct()->get());
    }

    function pluck(){
        $names = Database::table('products')->pluck('size');

        foreach ($names as $name) {
            echo "$name <br/>";
        }
    }

    function get_product($id){       
        // Include deleted items
        Debug::dd(Database::table('products')->where(['id' => $id])->showDeleted()->get());
    }
    
    function exists(){
       
        Debug::dd(Database::table('products')->where(['belongs_to' => 103])->exists());

        Debug::dd(Database::table('products')->where([ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ])->exists());

        $o = Database::table('other_permissions', 'op');
        Debug::dd($o ->join('folders', 'op.folder_id', '=',  'folders.id')
                        ->join('users', 'folders.belongs_to', '=', 'users.id')
                        ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                        //->join('roles', 'user_role.role_id', '=', 'roles.id') 
                        ->where([
                            ['guest', 1],
                            ['resource_table', 'products'],
                            ['r', 1]
                        ])->exists());
    }
           
    function first(){
        Debug::dd(Database::table('products')->where([ 
            ['cost', 50, '>='],
            ['cost', 500, '<='],
            ['belongs_to',  90]
        ])->first(['name', 'size', 'cost'])); 
    }

    function value(){
        Debug::dd(Database::table('products')->where([ 
            ['cost', 300, '>='],
            ['cost', 500, '<='],
            ['belongs_to',  90]
        ])->value('name')); 
    }

    function oldest(){
        // oldest first
        Debug::dd(Database::table('products')->oldest()->get());
    }

    function newest(){
        // newest, first result
        Debug::dd(Database::table('products')->newest()->first());
    }
    
    // random or rand
    function random(){
        Debug::dd(Database::table('products')->random()->limit(5)->get(['id', 'name']));

        Debug::dd(Database::table('products')->random()->select(['id', 'name'])->first());
    }

    function count(){
        $c = Database::table('users')
        ->where([ 'belongs_to'=> 160] )
        ->count();

        var_dump($c);
    }

    function count1(){
        // SELECT COUNT(*) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL 

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->count();

        Debug::dd(Database::getQueryLog());
    } 

    function count2(){
        // SELECT COUNT(DISTINCT description) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL  

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->distinct()
        ->count('description');
    }

    function avg(){
        // SELECT AVG(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->avg('cost');
    }

    function sum(){
        // SELECT SUM(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->sum('cost');
    }

    function min(){
        // SELECT MIN(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->min('cost');
    }

    function max(){
        // SELECT MIN(cost) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL; 

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->max('cost');
    }

    /*
        select and addSelect
    */
    function select() {
        Debug::dd(Database::table('products')->random()->select(['id', 'name'])->addSelect('cost')->first());
    }

    /*
        RAW select

        pluck() no se puede usar con selectRaw() si posee un "as" pero la forma de lograr lo mismo
        es seteando el "fetch mode" en "COLUMN"

        Investigar como funciona el pluck() de Larvel
        https://stackoverflow.com/a/40964361/980631
    */
    function select2() {
        Debug::dd(Database::table('products')->setFetchMode('COLUMN')
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get());
    }

    function select3() {
        Debug::dd(Database::table('products')->setFetchMode('COLUMN')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get());
    }

    function select3a() {
        Debug::dd(Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get());
    }

    function select3b() {
        Debug::dd(Database::table('products')->setFetchMode('COLUMN')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get());
    }

    function select4() {
        Debug::dd(Database::table('products')
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
        Debug::dd(Database::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])->count());
    }

    /*
        SELECT size, AVG(cost) FROM products GROUP BY size
    */
    function select_group_avg(){
        Debug::dd(Database::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])
        ->avg('cost'));    
    }

    function filter_products1(){
        Debug::dd(Database::table('products')->showDeleted()->where([ 
            ['size', '2L']
        ])->get());
    }
    
    function filter_products2(){
        Debug::dd(Database::table('products')
        ->where([ 
            ['name', ['Vodka', 'Wisky', 'Tekila','CocaCola']], // IN 
            ['locked', 0],
            ['belongs_to', 90]
        ])
        ->whereNotNull('description')
        ->get());
    }

    // SELECT * FROM products WHERE name IN ('CocaCola', 'PesiLoca') OR cost IN (100, 200)  OR cost >= 550 AND deleted_at IS NULL
    function filter_products3(){

        Debug::dd(Database::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca']], 
            ['cost', 550, '>='],
            ['cost', [100, 200]]
        ], 'OR')->get());    
    }

    function filter_products4(){    
        Debug::dd(Database::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca', 'Wisky', 'Vodka'], 'NOT IN']
        ])->get());
    }

    function filter_products5(){
        // implicit 'AND'
        Debug::dd(Database::table('products')->where([ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ])->get());        
    }

    function filter_products6(){
        Debug::dd(Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<=']
        ])->get());            
    }

    // WHERE IN
    function where1(){
        Debug::dd(Database::table('products')->where(['size', ['0.5L', '3L'], 'IN'])->get());
    }

    // WHERE IN
    function where2(){
        Debug::dd(Database::table('products')->where(['size', ['0.5L', '3L']])->get());
    }

    // WHERE IN
    function where3(){
        Debug::dd(Database::table('products')->whereIn('size', ['0.5L', '3L'])->get());
    }

    //WHERE NOT IN
    function where4(){
        Debug::dd(Database::table('products')->where(['size', ['0.5L', '3L'], 'NOT IN'])->get());
    }

    //WHERE NOT IN
    function where5(){
        Debug::dd(Database::table('products')->whereNotIn('size', ['0.5L', '3L'])->get());
    }

    // WHERE NULL
    function where6(){  
        Debug::dd(Database::table('products')->where(['workspace', null])->get());   
    }

    // WHERE NULL
    function where7(){  
        Debug::dd(Database::table('products')->whereNull('workspace')->get());
    }

    // WHERE NOT NULL
    function where8(){  
        Debug::dd(Database::table('products')->where(['workspace', null, 'IS NOT'])->get());   
    }

    // WHERE NOT NULL
    function where9(){  
        Debug::dd(Database::table('products')->whereNotNull('workspace')->get());
    }

    // WHERE BETWEEN
    function where10(){
        Debug::dd(Database::table('products')
        ->select(['name', 'cost'])
        ->whereBetween('cost', [100, 250])->get());
    }

    // WHERE BETWEEN
    function where11(){
        Debug::dd(Database::table('products')
        ->select(['name', 'cost'])
        ->whereNotBetween('cost', [100, 250])->get());
    }
    
    function where12(){
        Debug::dd(Database::table('products')
        ->find(103));
    }

    function where13(){
        Debug::dd(Database::table('products')
        ->where(['cost', 150])
        ->value('name'));
    }

    /*
        SELECT  name, cost, id FROM products WHERE belongs_to = '90' AND (cost >= 100 AND cost < 500) AND description IS NOT NULL
    */
    function where14(){
        Debug::dd(Database::table('products')->showDeleted()
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
        Debug::dd(Database::table('products')->showDeleted()
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
        Debug::dd(Database::table('products')->showDeleted()
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
        Debug::dd(Database::table('products')->showDeleted()
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
        Debug::dd(Database::table('products')
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

        $rows = Database::table('users')->setFetchMode('ASSOC')->unhide(['password'])
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

        $rows = Database::table('users')->setFetchMode('ASSOC')
            ->where([ 'email'=> $email ]) 
            ->orWhere(['username' => $username ])
            ->setValidator((new Validator())->setRequired(false))  
            ->get();

        Debug::dd($rows);
    }


    // SELECT * FROM products WHERE ((cost < IF(size = "1L", 300, 100) AND size = '1L' ) AND belongs_to = 90) AND deleted_at IS NULL ORDER BY cost ASC
    function where_raw(){
        Debug::dd(Database::table('products')
        ->where(['belongs_to' => 90])
        ->whereRaw('cost < IF(size = "1L", ?, 100) AND size = ?', [300, '1L'])
        ->orderBy(['cost' => 'ASC'])
        ->get());
    }
   
    /*
        SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname IS NOT NULL);
    */
    function where_raw2(){
        Debug::dd(Database::table('products')->showDeleted()
        ->whereRaw('EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?  )', ['AB'])
        ->get());
    }


    /*
        WHERE EXISTS

        SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname IS NOT NULL);
    */
    function where_exists(){
        Debug::dd(Database::table('products')->showDeleted()
        ->whereExists('(SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?)', ['AB'])
        ->get());
    }

    /*
        SELECT * FROM products WHERE 1 = 1 AND deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
    */
    function order(){    
        Debug::dd(Database::table('products')->orderBy(['cost'=>'ASC', 'id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::dd(Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::dd(Database::table('products')->orderBy(['cost'=>'ASC'])->take(4)->offset(1)->get(null, ['id'=>'DESC']));

        Debug::dd(Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::dd(Database::table('products')->take(4)->offset(1)->get(null, ['cost'=>'ASC', 'id'=>'DESC']));
    }

    /*
        RAW
        
        SELECT * FROM products WHERE 1 = 1 AND deleted_at IS NULL ORDER BY locked + active ASC
    */
    function order2(){
        Debug::dd(Database::table('products')->orderByRaw('locked * active DESC')->get()); 
    }

    function grouping(){
        Debug::dd(Database::table('products')->where([ 
            ['cost', 100, '>=']
        ])->orderBy(['size' => 'DESC'])->groupBy(['size'])->select(['size'])->avg('cost'));
    }

    function where(){        

        // Ok
        Debug::dd(Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to',  90]
        ])->get());  
        

        /*    
        // No es posible mezclar arrays asociativos y no-asociativos 
        Debug::dd(Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to' =>  90]
        ])->get());
        */        

        // Ok
        Debug::dd(Database::table('products')
        ->where([ 
                ['cost', 150, '>='],
                ['cost', 270, '<=']            
            ])
        ->where(['belongs_to' =>  90])->get());         
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
    function having(){        
        Debug::dd(Database::table('products')
            ->groupBy(['cost', 'size'])
            ->having(['cost', 100])
            ->get(['cost', 'size']));
    }    

    /*
        HAVING ... OR ... OR ...

        SELECT  cost, size, belongs_to FROM products WHERE deleted_at IS NULL GROUP BY cost,size,belongs_to HAVING belongs_to = 90 AND (cost >= 100 OR size = '1L') ORDER BY size DESC
    */
    function having2(){
        Debug::dd(Database::table('products')
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
        Debug::dd(Database::table('products')
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
        Debug::dd(Database::table('products')
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
        Debug::dd(Database::table('products')
            ->selectRaw('SUM(cost) as total_cost')
            ->where(['size', '1L'])
            ->groupBy(['belongs_to']) 
            ->havingRaw('SUM(cost) > ?', [500])
            ->limit(3)
            ->offset(1)
            ->get());
    }

    function joins(){
        $o = Database::table('other_permissions', 'op');
        $rows =   $o->join('folders', 'op.folder_id', '=',  'folders.id')
                    ->join('users', 'folders.belongs_to', '=', 'users.id')
                    ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
                    ->where([
                        ['guest', 1],
                        ['resource_table', 'products'],
                        ['r', 1]
                    ])
                    ->orderByRaw('users.id DESC')
                    ->get();  
        
        Debug::dd($rows);
    }
 
    function get_nulls(){    
        // Get products where workspace IS NULL
        Debug::dd(Database::table('products')->where(['workspace', null])->get());   
   
        // Or
        Debug::dd(Database::table('products')->whereNull('workspace')->get());
    }

    /*
        Pretty response 
    */
    function get_users(){
        $array = Database::table('users')->orderBy(['id'=>'DESC'])->get();

        echo '<pre>';
        Factory::response()->setPretty(true)->send($array);
        echo '</pre>';
    }

    function get_user($id){
        $u = Database::table('users');
        $u->unhide(['password']);
        $u->hide(['username', 'confirmed_email', 'firstname','lastname', 'deleted_at', 'belongs_to']);
        
        Debug::dd($u->where(['id'=>$id])->get());
    }

    function del_user($id){
        $u = Database::table('users');
        $ok = (bool) $u->where(['id' => $id])->delete(false);
        
        Debug::dd($ok);
    }

 
    function update_user($id) {
        $u = Database::table('users');

        $count = $u->where(['firstname' => 'HHH', 'lastname' => 'AAA', 'id' => 17])->update(['firstname'=>'Nico', 'lastname'=>'Buzzi', 'belongs_to' => 17]);
        
        Debug::dd($count);
    }

    function update_user2() 
    {
        $firstname = '';
        for ($i=0;$i<20;$i++)
            $firstname .= chr(rand(97,122));

        $lastname = strtoupper($firstname);    

        $u = Database::table('users');

        $ok = $u->where([ [ 'email', 'nano@'], ['deleted_at', NULL] ])
        ->update([ 
                    'firstname' => $firstname, 
                    'lastname' => $lastname
        ]);
        
        Debug::dd($ok);
    }

    function update_users() {
        $u = Database::table('users');
        $count = $u->where([ ['lastname', ['AAA', 'Buzzi']] ])->update(['firstname'=>'Nicos']);
        
        Debug::dd($count);
    }

    function create_user($email, $password, $firstname, $lastname)
     {        
        for ($i=0;$i<20;$i++)
            $email = chr(rand(97,122)) . $email;
        
        $u = Database::table('users');
        //$u->fill(['email']);
        //$u->unfill(['password']);
        $id = $u->create(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname]);
        
        Debug::dd($id);
    }

    function fillables(){
        $str = '';
        for ($i=0;$i<20;$i++)
            $str .= chr(rand(97,122));

        $p = Database::table('products');
        $affected = $p->where(['id' => 121])->update([
            'id' => 500,
            'description' => $str
        ]);

        // Show result
        $rows = Database::table('products')->where(['id' => 500])->get();
        Debug::dd($rows);
    }

    function update_products() {
        $p = Database::table('products');
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

    function validacion1(){
        $u = Database::table('users')->setValidator(new Validator());
        $affected = $u->where(['email' => 'nano@'])->update(['firstname' => 'NA']);
    }

    function validacion2(){
        $u = Database::table('users')->setValidator(new Validator());
        $affected = $u->where(['email' => 'nano@'])->update(['firstname' => 'NA']);
    }

    function validacion3(){
        $p = Database::table('products')->setValidator(new Validator());
        $rows = $p->where(['cost' => '100X', 'belongs_to' => 90])->get();

        Debug::dd($rows);
    }

    function validacion4(){
        $p = Database::table('products')->setValidator(new Validator());
        $affected = $p->where(['cost' => '100X', 'belongs_to' => 90])->delete();

        Debug::dd($affected);
    }
  
    /*
        Intento #1 de sub-consultas en el WHERE

        SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL);

    */
    function sub(){
        $st = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
        ->whereRaw('belongs_to IN (SELECT id FROM users WHERE password IS NULL)')
        ->get();

        Debug::dd($st);    
    }

    /*
        Intento #2 de sub-consultas en el WHERE

        SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL);

    */
    function sub2(){
        $sub = Database::table('users')
        ->select(['id'])
        ->whereRaw('password IS NULL');

        $st = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
        ->whereRaw("belongs_to IN ({$sub->toSql()})")
        ->get();

        Debug::dd($st);    
    }

    /*
        Subconsultas en el WHERE --ok
    */
    function sub3(){
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

        Debug::dd($res);    
    }

    /*
        SELECT  id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT  users.id FROM users  INNER JOIN user_roles ON users.id=user_roles.user_id WHERE confirmed_email = 1  AND password < 100 AND role_id = 2  )  AND size = '1L' ORDER BY id DESC

    */
    function sub3b(){
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

        Debug::dd($res);    
    }

    function sub3c(){
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

        Debug::dd($res);    
    }

    /*
        RAW select

        SELECT COUNT(*)  FROM (SELECT size FROM products GROUP BY size) as sub;
    */
    function sub4(){
        $sub = Database::table('products')->showDeleted()
        ->select(['size'])
        ->groupBy(['size']);

        $conn = Database::getConnection();

        $m = new \simplerest\core\Model($conn);
        $res = $m->fromRaw("({$sub->toSql()}) as sub")->count();

        Debug::dd($res);    
    }

    /*
        SELECT  COUNT(*) FROM (SELECT  size FROM products WHERE belongs_to = 90 GROUP BY size ) as sub WHERE 1 = 1
    */
    function sub4a(){
        $sub = Database::table('products')->showDeleted()
        ->select(['size'])
        ->where(['belongs_to', 90])
        ->groupBy(['size']);

        $conn = Database::getConnection();

        $res = (new \simplerest\core\Model($conn))
        ->fromRaw("({$sub->toSql()}) as sub")
        ->mergeBindings($sub)
        ->count();

        Debug::dd($res);    
    }

    /*
        FROM RAW

        SELECT  COUNT(*) FROM (SELECT  size FROM products WHERE belongs_to = 90 GROUP BY size ) as sub WHERE 1 = 1
    */
    function sub4b(){
        $sub = Database::table('products')->showDeleted()
        ->select(['size'])
        ->where(['belongs_to', 90])
        ->groupBy(['size']);

        $res = Database::table("({$sub->toSql()}) as sub")
        ->mergeBindings($sub)
        ->count();

        Debug::dd($res);    
    }
    
    /*
        UNION

        SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 90 UNION SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 4 ORDER by id ASC LIMIT 5;
    */
    function union(){
        $uno = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 90]);

        $dos = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 4])
        ->union($uno)
        ->orderBy(['id' => 'ASC'])
        ->limit(5)
        ->get();

        Debug::dd($dos);
    }

    /*
        UNION ALL
    */
    function union_all(){
        $uno = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 90]);

        $dos = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['cost', 200, '>='])
        ->unionAll($uno)
        ->orderBy(['id' => 'ASC'])
        ->limit(5)
        ->get();

        Debug::dd($dos);
    }

    function valida(){
        Debug::debug ( 
            (new Validator())->validate([
                'id'   => ['type' => 'int', 'required' => true],
                'cost' => ['type'=> 'int'],
                'size' => ['type'=> 'str'],
                'name' => ['type'=> 'str', 'min' => 3]
            ],
            [ 
                'cost' => ['150K', '200'],
                'size' => ['1L'],
                'name' => ['ab']
            ])
        );
    }

    function testget(){
        // SELECT * FROM products WHERE deleted_at IS NULL
        $query = Database::table('products');
        Debug::dd($query->dd());

         // SELECT * FROM products
         $query = Database::table('products')->showDeleted();
         Debug::dd($query->dd());

        //SELECT DISTINCT size FROM products WHERE deleted_at IS NULL  
        $query = Database::table('products')->select(['size'])->distinct();
        Debug::dd($query->dd());

        // SELECT DISTINCT size, cost FROM products WHERE deleted_at IS NULL
        $query = Database::table('products')->select(['size', 'cost'])->distinct();
        Debug::dd($query->dd());
       
        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at DESC
        $query = Database::table('products')->oldest();
        Debug::dd($query->dd());
        
        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY created_at ASC
        $query = Database::table('products')->newest();
        Debug::dd($query->dd());

        // SELECT id, name FROM products WHERE deleted_at IS NULL ORDER BY RAND() 
        $query = Database::table('products')->random()->select(['id', 'name']);
        Debug::dd($query->dd());

        // SELECT id, name FROM products WHERE deleted_at IS NULL ORDER BY RAND()
        Debug::dd($query->getLog());       

        // SELECT COUNT(*) FROM users WHERE belongs_to = 160 AND deleted_at IS NULL 
        $query = Database::table('users')
        ->where([ 'belongs_to'=> 160] )
        ->count();
        Debug::dd(Database::getQueryLog());

        // SELECT COUNT(modified_at) FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) 
        Database::table('products')->showDeleted()
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->count('modified_at');
        Debug::dd(Database::getQueryLog());

        // SELECT COUNT(DISTINCT description) FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) 
        Database::table('products')->showDeleted()
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->distinct()
        ->count('description');
        Debug::dd(Database::getQueryLog());
        
        // SELECT COUNT(*) FROM products WHERE (cost >= 200 OR size = 2L) AND deleted_at IS NULL 
        $query = Database::table('products')
        ->where([ [ 'cost', 200, '>='], [ 'size', '2L'] ], 'OR')
        ->count();
        Debug::dd(Database::getQueryLog());

        // SELECT COUNT(*) FROM products WHERE (cost >= 200 OR size = 2L) 
        $query = Database::table('products')->showDeleted()
        ->where([ [ 'cost', 200, '>='], [ 'size', '2L'] ], 'OR')
        ->count();
        Debug::dd(Database::getQueryLog());

        // SELECT COUNT(DISTINCT description) FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL 
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->distinct()
        ->count('description');
        Debug::dd(Database::getQueryLog());

        // SELECT AVG(cost) FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->avg('cost');
        Debug::dd(Database::getQueryLog());

        // SELECT SUM(cost) FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->sum('cost');
        Debug::dd(Database::getQueryLog());

        // SELECT MIN(cost) FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL 
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->min('cost');
        Debug::dd(Database::getQueryLog());

        // SELECT MAX(cost) FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->max('cost');
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost DESC
        Database::table('products')->orderBy(['cost' => 'DESC'])->get();
        Debug::dd(Database::getQueryLog());        

        // SELECT * FROM products WHERE deleted_at IS NULL LIMIT 20, 10
        Database::table('products')->limit(10)->offset(20)->get();
        Debug::dd(Database::getQueryLog());

        // SELECT cost FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL LIMIT 20, 10
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->limit(10)->offset(20)
        ->get(['cost']);
        Debug::dd(Database::getQueryLog());

        // SELECT id, name, cost FROM products WHERE deleted_at IS NULL ORDER BY RAND() LIMIT 0, 1
        Database::table('products')->random()->select(['id', 'name'])->addSelect('cost')->first();
        Debug::dd(Database::getQueryLog());

        // SELECT cost * 1.05 as cost_after_inc FROM products WHERE deleted_at IS NULL
        Database::table('products')->setFetchMode('COLUMN')
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT cost * 1.05 as cost_after_inc FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT DISTINCT cost * 1.05 as cost_after_inc, name, description, size, cost, workspace, active, locked, belongs_to FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL 
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get();
        Debug::dd(Database::getQueryLog());

        // SELECT DISTINCT cost * 1.05 as cost_after_inc, name, size FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->select(['name', 'size'])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get();
        Debug::dd(Database::getQueryLog());

        // SELECT DISTINCT cost * 1.05 as cost_after_inc, name, size FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()
        ->addSelect('name')
        ->addSelect('size')
        ->get();
        Debug::dd(Database::getQueryLog());
    
        // SELECT cost * 1.05 as cost_after_inc, name, cost FROM products WHERE (cost >= 100 AND size = 1L AND belongs_to = 90) AND deleted_at IS NULL  
        Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])
        ->addSelect('name')
        ->addSelect('cost')
        ->get();
        Debug::dd(Database::getQueryLog());

        // SELECT size, COUNT(*) FROM products GROUP BY size
        Database::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])->count();
        Debug::dd(Database::getQueryLog());

        // SELECT size, AVG(cost) FROM products GROUP BY size
        Database::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])
        ->avg('cost');
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size = 2L
        Database::table('products')->showDeleted()->where([ 
            ['size', '2L']
        ])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size = 2L
        Database::table('products')->showDeleted()->where([ 
            'size', '2L'
        ])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size = 2L 
        Database::table('products')->showDeleted()->where( 
            ['size' => '2L']
        )->get();
        Debug::dd(Database::getQueryLog());
        
        // SELECT * FROM products WHERE (name IN ('Vodka', 'Wisky', 'Tekila', 'CocaCola') AND locked = 0 AND belongs_to = 90) AND description IS NOT AND deleted_at IS NULL
        Database::table('products')
        ->where([ 
            ['name', ['Vodka', 'Wisky', 'Tekila','CocaCola']], // IN 
            ['locked', 0],
            ['belongs_to', 90]
        ])
        ->whereNotNull('description')
        ->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE (name IN ('CocaCola', 'PesiLoca') OR cost IN (100, 200) OR cost >= 550) AND deleted_at IS NULL 
        Database::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca']], 
            ['cost', 550, '>='],
            ['cost', [100, 200]]
        ], 'OR')->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE name NOT IN ('CocaCola', 'PesiLoca', 'Wisky', 'Vodka') AND deleted_at IS NULL
        Database::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca', 'Wisky', 'Vodka'], 'NOT IN']
        ])->get();
        Debug::dd(Database::getQueryLog());
 
        // SELECT * FROM products WHERE (cost < 200 AND name = CocaCola) AND deleted_at IS NULL 
        Database::table('products')->where([ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE (cost >= 200 AND cost <= 270) AND deleted_at IS NULL 
        Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<=']
        ])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size IN ('0.5L', '3L') AND deleted_at IS NULL 
        Database::table('products')->where(['size', ['0.5L', '3L'], 'IN'])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size IN ('0.5L', '3L') AND deleted_at IS NULL
        Database::table('products')->where(['size', ['0.5L', '3L']])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size IN ('0.5L', '3L') AND deleted_at IS NULL 
        Database::table('products')->whereIn('size', ['0.5L', '3L'])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size NOT IN ('0.5L', '3L') AND deleted_at IS NULL
        Database::table('products')->where(['size', ['0.5L', '3L'], 'NOT IN'])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE size NOT IN ('0.5L', '3L') AND deleted_at IS NULL
        Database::table('products')->whereNotIn('size', ['0.5L', '3L'])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE workspace IS AND deleted_at IS NULL 
        Database::table('products')->where(['workspace', null])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE workspace IS AND deleted_at IS NULL 
        Database::table('products')->whereNull('workspace')->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE workspace IS NOT AND deleted_at IS NULL
        Database::table('products')->where(['workspace', null, 'IS NOT'])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE workspace IS NOT AND deleted_at IS NULL 
        Database::table('products')->whereNotNull('workspace')->get();
        Debug::dd(Database::getQueryLog());

        // SELECT name, cost FROM products WHERE cost >= 100 AND cost <= 250 AND deleted_at IS NULL 
        Database::table('products')
        ->select(['name', 'cost'])
        ->whereBetween('cost', [100, 250])->get();
        Debug::dd(Database::getQueryLog());

        // SELECT name, cost FROM products WHERE (cost < 100 OR cost > 250) AND deleted_at IS NULL 
        Database::table('products')
        ->select(['name', 'cost'])
        ->whereNotBetween('cost', [100, 250])->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM products WHERE id = 103 AND deleted_at IS NULL 
        Database::table('products')
        ->find(103);
		Debug::dd(Database::getQueryLog());

        // SELECT name FROM products WHERE cost = 150 AND deleted_at IS NULL LIMIT 0, 1
        Database::table('products')
        ->where(['cost', 150])
        ->value('name');
		Debug::dd(Database::getQueryLog());
    
        // SELECT name, cost, id FROM products WHERE belongs_to = 90 AND (cost >= 100 AND cost < 500) AND description IS NOT NULL 
        Database::table('products')->showDeleted()
        ->select(['name', 'cost', 'id'])
        ->where(['belongs_to', 90])
        ->where([ 
            ['cost', 100, '>='],
            ['cost', 500, '<']
        ])
        ->whereNotNull('description')
        ->get();
		Debug::dd(Database::getQueryLog());
   
         // SELECT name, cost, id FROM products WHERE belongs_to = 90 AND (name IN ('CocaCola', 'PesiLoca') OR cost >= 550 OR cost < 100) AND description IS NOT NULL  
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
		Debug::dd(Database::getQueryLog());
    
        // SELECT name, cost, id FROM products WHERE belongs_to = 90 OR name IN ('CocaCola', 'PesiLoca') OR (cost <= 550 AND cost >= 100)
        Database::table('products')->showDeleted()
        ->select(['name', 'cost', 'id'])
        ->where(['belongs_to', 90])
        ->orWhere(['name', ['CocaCola', 'PesiLoca']])
        ->orWhere([
            ['cost', 550, '<='],
            ['cost', 100, '>=']
        ])
        ->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT name, cost, id, description FROM products WHERE description IS NOT NULL OR (cost >= 100 AND cost < 500 
        Database::table('products')->showDeleted()
        ->select(['name', 'cost', 'id', 'description'])
        ->whereNotNull('description')
        ->orWhere([ 
                    ['cost', 100, '>='],
                    ['cost', 500, '<']
        ])        
        ->get();
		Debug::dd(Database::getQueryLog());
 
        // SELECT id, name, cost, description FROM products WHERE belongs_to = 90 AND (name IN ('CocaCola', 'PesiLoca') OR cost >= 550 OR cost < 100) AND description IS NOT NULL AND deleted_at IS NULL
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
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM users WHERE (email = nano@g.c OR username = nano@g.c) AND deleted_at IS NULL 
        Database::table('users')->setFetchMode('ASSOC')->unhide(['password'])
            ->where([ 'email'=> 'nano@g.c', 
                      'username' => 'nano@g.c' 
            ], 'OR') 
            ->setValidator((new Validator())->setRequired(false))  
            ->get();

		Debug::dd(Database::getQueryLog());
    
        // SELECT id, username, email, confirmed_email, firstname, lastname, deleted_at, belongs_to FROM users WHERE email = nano@g.c OR username = nano AND deleted_at IS NULL 
        $rows = Database::table('users')
            ->where([ 'email'=> 'nano@g.c' ]) 
            ->orWhere(['username' => 'nano' ])
            ->setValidator((new Validator())->setRequired(false))  
            ->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM products WHERE (cost < IF(size = "1L", 300, 100) AND size = 1L) AND belongs_to = 90 AND deleted_at IS NULL ORDER BY cost ASC
        Database::table('products')
        ->where(['belongs_to' => 90])
        ->whereRaw('cost < IF(size = "1L", ?, 100) AND size = ?', [300, '1L'])
        ->orderBy(['cost' => 'ASC'])
        ->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = AB )
        Database::table('products')->showDeleted()
        ->whereRaw('EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?  )', ['AB'])
        ->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM products WHERE EXISTS (SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = AB)
        Database::table('products')->showDeleted()
        ->whereExists('(SELECT 1 FROM users WHERE products.belongs_to = users.id AND users.lastname = ?)', ['AB'])
        ->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
        Database::table('products')->orderBy(['cost'=>'ASC', 'id'=>'DESC'])->take(4)->offset(1)->get();
		Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
        Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get();
		Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
        Database::table('products')->orderBy(['cost'=>'ASC'])->take(4)->offset(1)->get(null, ['id'=>'DESC']);
		Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
        Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get();
		Debug::dd(Database::getQueryLog());

        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
        Database::table('products')->take(4)->offset(1)->get(null, ['cost'=>'ASC', 'id'=>'DESC']);
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM products WHERE deleted_at IS NULL ORDER BY locked * active DESC
        Database::table('products')->orderByRaw('locked * active DESC')->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT size, AVG(cost) FROM products WHERE cost >= 100 AND deleted_at IS NULL GROUP BY size ORDER BY size DESC
        Database::table('products')->where([ 
            ['cost', 100, '>=']
        ])->orderBy(['size' => 'DESC'])->groupBy(['size'])->select(['size'])->avg('cost');
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM products WHERE (cost >= 200 AND cost <= 270 AND belongs_to = 90) AND deleted_at IS NULL 
        Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to',  90]
        ])->get(); 
		Debug::dd(Database::getQueryLog());
        
        // SELECT * FROM products WHERE (cost >= 150 AND cost <= 270) AND belongs_to = 90 AND deleted_at IS NULL 
        Database::table('products')
        ->where([ 
                ['cost', 150, '>='],
                ['cost', 270, '<=']            
            ])
        ->where(['belongs_to' =>  90])->get();
		Debug::dd(Database::getQueryLog());		
    
        // SELECT cost, size FROM products WHERE deleted_at IS NULL GROUP BY cost,size HAVING cost = 100
        Database::table('products')
            ->groupBy(['cost', 'size'])
            ->having(['cost', 100])
            ->get(['cost', 'size']);
		Debug::dd(Database::getQueryLog());
        
        // SELECT cost, size, belongs_to FROM products WHERE deleted_at IS NULL GROUP BY cost,size,belongs_to HAVING belongs_to = 90 AND (cost >= 100 OR size = 1L) ORDER BY size DESC
        Database::table('products')
            ->groupBy(['cost', 'size', 'belongs_to'])
            ->having(['belongs_to', 90])
            ->having([  
                        ['cost', 100, '>='],
                        ['size' => '1L'] ], 
            'OR')
            ->orderBy(['size' => 'DESC'])
            ->get(['cost', 'size', 'belongs_to']); 
		Debug::dd(Database::getQueryLog());
    
        // SELECT cost, size, belongs_to FROM products WHERE deleted_at IS NULL GROUP BY cost,size,belongs_to HAVING belongs_to = 90 OR cost >= 100 OR size = 1L ORDER BY size DESC
        Database::table('products')
            ->groupBy(['cost', 'size', 'belongs_to'])
            ->having(['belongs_to', 90])
            ->orHaving(['cost', 100, '>='])
            ->orHaving(['size' => '1L'])
            ->orderBy(['size' => 'DESC'])
            ->get(['cost', 'size', 'belongs_to']); 
		Debug::dd(Database::getQueryLog());
    
        // SELECT cost, size, belongs_to FROM products WHERE deleted_at IS NULL GROUP BY cost,size,belongs_to HAVING belongs_to = 90 OR (cost >= 100 AND size = 1L) ORDER BY size DESC
        Database::table('products')
            ->groupBy(['cost', 'size', 'belongs_to'])
            ->having(['belongs_to', 90])
            ->orHaving([  
                        ['cost', 100, '>='],
                        ['size' => '1L'] ] 
            )
            ->orderBy(['size' => 'DESC'])
            ->get(['cost', 'size', 'belongs_to']); 
		Debug::dd(Database::getQueryLog());
    
        // SELECT SUM(cost) as total_cost FROM products WHERE size = 1L AND deleted_at IS NULL GROUP BY belongs_to HAVING SUM(cost) > 500 LIMIT 1, 3
        Database::table('products')
            ->selectRaw('SUM(cost) as total_cost')
            ->where(['size', '1L'])
            ->groupBy(['belongs_to']) 
            ->havingRaw('SUM(cost) > ?', [500])
            ->limit(3)
            ->offset(1)
            ->get();
		Debug::dd(Database::getQueryLog());
    
        // SELECT * FROM other_permissions as op INNER JOIN folders ON op.folder_id=folders.id INNER JOIN users ON folders.belongs_to=users.id INNER JOIN user_roles ON users.id=user_roles.user_id WHERE (guest = 1 AND resource_table = products AND r = 1) ORDER BY users.id DESC
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
        Debug::dd(Database::getQueryLog());
        
        // SELECT * FROM products WHERE workspace IS NULL AND deleted_at IS NULL L 
        Database::table('products')->where(['workspace', null])->get();  
        Debug::dd(Database::getQueryLog());	

        // SELECT * FROM products WHERE workspace IS NULL AND deleted_at IS NULL 
        Database::table('products')->whereNull('workspace')->get();
        Debug::dd(Database::getQueryLog());     
        
        // SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL) 
        Database::table('products')->showDeleted()
            ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
            ->whereRaw('belongs_to IN (SELECT id FROM users WHERE password IS NULL)')
            ->get();
	    Debug::dd(Database::getQueryLog());

    } // end fn
    

    function testsubqueries(){   
        
        // SELECT id, name, size, cost, belongs_to FROM products WHERE belongs_to IN (SELECT id FROM users WHERE password IS NULL AND deleted_at IS NULL ) 

        $sub = Database::table('users')
        ->select(['id'])
        ->whereRaw('password IS NULL');

        Database::table('products')->showDeleted()
        ->select(['id', 'name', 'size', 'cost', 'belongs_to'])
        ->whereRaw("belongs_to IN ({$sub->toSql()})")
        ->get();

        Debug::dd(Database::getQueryLog());   

        // SELECT id, name, size, cost, belongs_to FROM products WHERE (belongs_to IN (SELECT id FROM users WHERE (confirmed_email = 1) AND password < 100 )) AND size = 1L 
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

        Debug::dd(Database::getQueryLog());  

        // SELECT id, name, size, cost, belongs_to FROM products WHERE (belongs_to IN (SELECT users.id FROM users INNER JOIN user_roles ON users.id=user_roles.user_id WHERE (confirmed_email = 1) AND password < 100 AND role_id = 2 )) AND size = 1L ORDER BY id DESC
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

        Debug::dd(Database::getQueryLog());    
        

        // SELECT size, AVG(cost) FROM products WHERE belongs_to IN (SELECT users.id FROM users INNER JOIN user_roles ON users.id=user_roles.user_id WHERE (confirmed_email = 1) AND password < 100 AND role_id = 3 ) GROUP BY size 
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

        Debug::dd(Database::getQueryLog());

        /*
        No aparece el COUNT(*)  !!!!

        // SELECT size FROM products GROUP BY size 
        $sub = Database::table('products')->showDeleted()
        ->select(['size'])
        ->groupBy(['size']);
    
        $conn = Database::getConnection();
    
        $m = new \simplerest\core\Model($conn);
        $res = $m->fromRaw("({$sub->toSql()}) as sub")->count();
    
        Debug::dd(Database::getQueryLog());  
        */

        /*
        No aparece el COUNT(*)  !!!!

        $sub = Database::table('products')->showDeleted()
        ->select(['size'])
        ->where(['belongs_to', 90])
        ->groupBy(['size']);

        $conn = Database::getConnection();

        $res = (new \simplerest\core\Model($conn))
        ->fromRaw("({$sub->toSql()}) as sub")
        ->mergeBindings($sub)
        ->count();

        Debug::dd(Database::getQueryLog());
        */

         /*
        No aparece el COUNT(*)  !!!!
        $sub = Database::table('products')->showDeleted()
        ->select(['size'])
        ->where(['belongs_to', 90])
        ->groupBy(['size']);

        $res = Database::table("({$sub->toSql()}) as sub")
        ->mergeBindings($sub)
        ->count();

        Debug::dd(Database::getQueryLog());
        */
    }

    function testunion(){
        // SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 4 UNION SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 0 ORDER BY id ASC LIMIT 5, ?

        // <--- corregir paginación !!!! no puede quedar como ? por falta de offset
        $uno = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 90]);

        $dos = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 4])
        ->union($uno)
        ->orderBy(['id' => 'ASC'])
        ->limit(5)
        ->get();

        Debug::dd(Database::getQueryLog());

        // SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 4 UNION SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 0 ORDER BY id ASC LIMIT 5, ?
        $uno = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs_to', 90]);

        $dos = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['cost', 200, '>='])
        ->unionAll($uno)
        ->orderBy(['id' => 'ASC'])
        ->limit(5)
        ->get();

        Debug::dd(Database::getQueryLog());
    }

    function testdelete(){
        $u = Database::table('users');
        $u->where(['id' => 100000])->delete(false);
        Debug::dd(Database::getQueryLog());
    }

    function testcreate(){       
        $id = Database::table('users')->create(['email'=> 'testing_create@g.com', 'password'=>'pass', 'firstname'=>'Jhon', 'lastname'=>'Doe', 'username' => 'doe1979']);
        Debug::dd(Database::getQueryLog());
        
        $ok = (bool) Database::table('users')->where(['id' => $id])->delete(false);        
        Debug::dd(Database::getQueryLog());
        Debug::dd($ok);
    }

    function testupdate(){
        $u = Database::table('users');
        $u->where(['id' => 100000])->update(['firstname'=>'Nico', 'lastname'=>'Buzzi']);
        Debug::dd(Database::getQueryLog());

        $u->where([ ['lastname', ['AAA', 'Buzzi']] ])->update(['firstname'=>'Nicolay']);
        Debug::dd(Database::getQueryLog());
    }

    function testhide(){
        $u = Database::table('users');
        $u->unhide(['password']);
        $u->hide(['username', 'confirmed_email', 'firstname','lastname', 'deleted_at', 'belongs_to']);
        $u->where(['id'=>$id])->get();
        Debug::dd(Database::getQueryLog());
    }

    function testfill(){ 
        $u = Database::table('users');
        $id = $u->create(['email'=> 'testing@g.com', 'password'=>'pass', 'firstname'=>'Jhon', 'lastname'=>'Doe', 'confirmed_email' => 1]);
        Debug::dd(Database::getQueryLog());   
        
        $u = Database::table('users');
        $u->unfill(['password']);
        $id = $u->create(['email'=> 'testing@g.com', 'password'=>'pass', 'firstname'=>'Jhon', 'lastname'=>'Doe']);
        Debug::dd(Database::getQueryLog()); 
    }

       
}