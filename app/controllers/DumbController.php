<?php

namespace simplerest\controllers;

use simplerest\core\Request;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\models\ProductsModel;
use simplerest\libs\Factory;
use simplerest\libs\Debug;
use simplerest\core\Controller;
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
        return "$req[0] + $req[1] = " . $res;
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
        Debug::debug(Database::table('products')->get());
        //Debug::debug(Database::table('products')->setFetchMode('ASSOC')->get());
    }

    function distinct(){
        Debug::debug(Database::table('products')->distinct()->get(['size']));

        // Or
        Debug::debug(Database::table('products')->distinct(['size'])->get());

        // Or
        Debug::debug(Database::table('products')->select(['size'])->distinct()->get());
    }

    function distinct1(){
        Debug::debug(Database::table('products')->select(['size', 'cost'])->distinct()->get());
    }

    function distinct2(){
        Debug::debug(Database::table('users')->distinct()->get());
    }

    function distinct3(){
        Debug::debug(Database::table('products')->distinct()->get());
    }

    function pluck(){
        $names = Database::table('products')->pluck('size');

        foreach ($names as $name) {
            echo "$name <br/>";
        }
    }

    function get_product($id){       
        // Include deleted items
        Debug::debug(Database::table('products')->where(['id' => $id])->showDeleted()->get());
    }
    
    function exists(){
       
        Debug::debug(Database::table('products')->where(['belongs_to' => 103])->exists());

        Debug::debug(Database::table('products')->where([ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ])->exists());

        $o = Database::table('other_permissions', 'op');
        Debug::debug($o ->join('folders', 'op.folder_id', '=',  'folders.id')
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
        Debug::debug(Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to',  90]
        ])->first(['name', 'size', 'cost'])); 
    }

    function oldest(){
        // oldest first
        Debug::debug(Database::table('products')->oldest()->get());
    }

    function newest(){
        // newest, first result
        Debug::debug(Database::table('products')->newest()->first());
    }
    
    // random or rand
    function random(){
        Debug::debug(Database::table('products')->random()->limit(5)->get(['id', 'name']));

        Debug::debug(Database::table('products')->random()->select(['id', 'name'])->first());
    }

    function count1(){
        // SELECT COUNT(*) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL 

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->count();
    }

    function count2(){
        // SELECT COUNT(modified_at) FROM products WHERE cost >= 100 AND size = '1L' AND belongs_to = 90 AND deleted_at IS NULL 

        echo Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->count('modified_at');
    }

    function count3(){
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
        Debug::debug(Database::table('products')->random()->select(['id', 'name'])->addSelect('cost')->first());
    }

    /*
        RAW select

        pluck() no se puede usar con selectRaw() si posee un "as" pero la forma de lograr lo mismo
        es seteando el "fetch mode" en "COLUMN"

        Investigar como funciona el pluck() de Larvel
        https://stackoverflow.com/a/40964361/980631
    */
    function select2() {
        Debug::debug(Database::table('products')->setFetchMode('COLUMN')
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get());
    }

    function select3() {
        Debug::debug(Database::table('products')->setFetchMode('COLUMN')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->get());
    }

    function select3a() {
        Debug::debug(Database::table('products')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get());
    }

    function select3b() {
        Debug::debug(Database::table('products')->setFetchMode('COLUMN')
        ->where([ ['cost', 100, '>='], ['size', '1L'], ['belongs_to', 90] ])
        ->selectRaw('cost * ? as cost_after_inc', [1.05])->distinct()->get());
    }

    function select4() {
        Debug::debug(Database::table('products')
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
        Debug::debug(Database::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])->count());
    }

    /*
        SELECT size, AVG(cost) FROM products GROUP BY size
    */
    function select_group_avg(){
        Debug::debug(Database::table('products')->showDeleted()
        ->groupBy(['size'])->select(['size'])
        ->avg('cost'));    
    }

    function filter_products(){
        $conn    = Database::getConnection();
        
        Debug::debug((new ProductsModel($conn))->showDeleted()->where([ 
            ['size', '2L']
        ])->get());
    
        Debug::debug((new ProductsModel($conn))->where([ 
            ['name', ['Vodka', 'Wisky', 'Tekila','CocaCola']], // IN 
            ['belongs_to', 90]
        ])->get());
    
        Debug::debug(Database::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca']], 
            ['cost', 550, '>='],
            ['cost', [100, 200]]
        ], 'OR')->get());    

        Debug::debug(Database::table('products')->where([ 
            ['name', ['CocaCola', 'PesiLoca', 'Wisky', 'Vodka'], 'NOT IN']
        ])->get());

        // implicit 'AND'
        Debug::debug(Database::table('products')->where([ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ])->get());        

        Debug::debug(Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<=']
        ])->get());            
    }


    function where_raw(){
        Debug::debug(Database::table('products')
        ->where(['belongs_to' => 90])
        ->whereRaw('cost < IF(size = "1L", ?, 100) AND size = ?', [300, '1L'])
        ->orderBy(['cost' => 'ASC'])
        ->get());
    }

    
    /*
        SELECT * FROM products WHERE 1 = 1 AND deleted_at IS NULL ORDER BY cost ASC, id DESC LIMIT 1, 4
    */
    function order(){    
        Debug::debug(Database::table('products')->orderBy(['cost'=>'ASC', 'id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::debug(Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::debug(Database::table('products')->orderBy(['cost'=>'ASC'])->take(4)->offset(1)->get(null, ['id'=>'DESC']));

        Debug::debug(Database::table('products')->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->get());

        Debug::debug(Database::table('products')->take(4)->offset(1)->get(null, ['cost'=>'ASC', 'id'=>'DESC']));
    }

    /*
        RAW
        
        SELECT * FROM products WHERE 1 = 1 AND deleted_at IS NULL ORDER BY locked + active ASC
    */
    function order2(){
        Debug::debug(Database::table('products')->orderByRaw('locked * active DESC')->get()); 
    }

    function grouping(){
        Debug::debug(Database::table('products')->where([ 
            ['cost', 100, '>=']
        ])->orderBy(['size' => 'DESC'])->groupBy(['size'])->select(['size'])->avg('cost'));
    }

    function where(){        

        // Ok
        Debug::debug(Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to',  90]
        ])->get());  
        

        /*    
        // No es posible mezclar arrays asociativos y no-asociativos 
        Debug::debug(Database::table('products')->where([ 
            ['cost', 200, '>='],
            ['cost', 270, '<='],
            ['belongs_to' =>  90]
        ])->get());
        */        

        // Ok
        Debug::debug(Database::table('products')
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
        Debug::debug(Database::table('products')
            ->groupBy(['cost', 'size'])
            ->having(['cost', 100])
            ->get(['cost', 'size']));
    }    

    function having2(){
        Debug::debug(Database::table('products')
            ->groupBy(['cost', 'size'])
            ->having([  ['cost', 100, '>='],
                        ['size' => '1L'] ], 'OR')
            ->orderBy(['size' => 'DESC'])
            ->get(['cost', 'size'])); 
    }

    /*
        RAW HAVING
    */
    function having3(){
        Debug::debug(Database::table('products')
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
        
        Debug::debug($rows);
    }
 
    function get_nulls(){    
        // Get products where workspace IS NULL
        Debug::debug(Database::table('products')->where(['workspace', null])->get());   
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
        $u->hide(['firstname','lastname']);
        
        Debug::debug($u->where(['id'=>$id])->get());
    }

    function del_user($id){
        $u = Database::table('users');
        $ok = (bool) $u->where(['id' => $id])->delete(false);
        
        Debug::debug($ok);
    }

 
    function update_user($id) {
        $u = Database::table('users');

        $count = $u->where(['firstname' => 'HHH', 'lastname' => 'AAA', 'id' => 17])->update(['firstname'=>'Nico', 'lastname'=>'Buzzi', 'belongs_to' => 17]);
        
        Debug::debug($count);
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
        
        Debug::debug($ok);
    }

    function update_users() {
        $u = Database::table('users');
        $count = $u->where([ ['lastname', ['AAA', 'Buzzi']] ])->update(['firstname'=>'Nicos']);
        
        Debug::debug($count);
    }

    function create_user($email, $password, $firstname, $lastname)
     {        
        for ($i=0;$i<20;$i++)
            $email = chr(rand(97,122)) . $email;
        
        $u = Database::table('users');
        //$u->fill(['email']);
        //$u->unfill(['password']);
        $id = $u->create(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname]);
        
        Debug::debug($id);
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
        Debug::debug($rows);
    }

    function update_products() {
        $p = Database::table('products');
        $count = $p->where([['cost', 100, '<'], ['belongs_to', 90]])->update(['description' => 'x_x']);
        
        Debug::debug($count);
    }

    function respuesta(){
        Factory::response()->sendError('Acceso no autorizado', 401, 'Header vacio');
    }
   
      // ok
    function sender(){
        Debug::debug(Utils::send_mail('boctulus@gmail.com', 'Pablo ZZ', 'Pruebita', 'Hola!<p/>Esto es una <b>prueba</b><p/>Chau'));     
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

        Debug::debug($rows);
    }

    function validacion4(){
        $p = Database::table('products')->setValidator(new Validator());
        $affected = $p->where(['cost' => '100X', 'belongs_to' => 90])->delete();

        Debug::debug($affected);
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

        Debug::debug($st);    
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

        Debug::debug($st);    
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

        Debug::debug($res);    
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

        Debug::debug($res);    
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

        Debug::debug($res);    
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

        Debug::debug($res);    
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

        Debug::debug($res);    
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

        Debug::debug($res);    
    }
    
    /*
        UNION

        SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 90 UNION SELECT id, name, description, belongs_to FROM products WHERE belongs_to = 4 ORDER by id DESC LIMIT 5;
    */
    function union(){
        $uno = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs', 90]);

        $dos = Database::table('products')->showDeleted()
        ->select(['id', 'name', 'description', 'belongs_to'])
        ->where(['belongs', 4])
        ->union($uno)
        ->orderBy(['id' => 'DESC'])
        ->get();

        Debug::debug($dos);
    }
   
}