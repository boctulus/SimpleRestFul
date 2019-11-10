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

class DumbController extends Controller
{
    function __construct()
    {
        parent::__construct();
    }

    function index(){
        return 'INDEX';
    }

    function sum($a, $b){
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

    function get_products(){
        $conn    = Database::getConnection();
        $p = new ProductsModel($conn);
    
        //$p->showDeleted(); 
        Debug::debug($p->fetchAll());
    }

    function order(){
        $conn    = Database::getConnection();
        $p = new ProductsModel($conn);
    
        Debug::debug($p->orderBy(['cost'=>'ASC', 'id'=>'DESC'])->take(4)->offset(1)->fetchAll());

        Debug::debug($p->orderBy(['cost'=>'ASC'])->orderBy(['id'=>'DESC'])->take(4)->offset(1)->fetchAll());

        Debug::debug($p->orderBy(['cost'=>'ASC'])->take(4)->offset(1)->fetchAll(null, ['id'=>'DESC']));

        Debug::debug($p->orderBy(['cost'=>'ASC'])->order(['id'=>'DESC'])->take(4)->offset(1)->fetchAll());

        Debug::debug($p->take(4)->offset(1)->fetchAll(null, ['cost'=>'ASC', 'id'=>'DESC']));
    }

    function get_product($id){
        $conn    = Database::getConnection();

        $p = new ProductsModel($conn);        
        $p->id = $id;
        $p->showDeleted();  
        $ok = $p->fetch(null); 

        if ($ok)
            Debug::debug($p);
    }

    function filter_products(){
        $conn    = Database::getConnection();
        
        Debug::debug((new ProductsModel($conn))->showDeleted()->where([ 
            ['size', '3L']
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

    function grouping(){
        Debug::debug(Database::table('products')->where([ 
            ['cost', 100, '>=']
        ])->orderBy(['size' => 'DESC'])->groupBy(['size'])->get(['size', 'AVG(cost)']));
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

        Debug::debug(Database::table('products')
            ->groupBy(['cost', 'size'])
            ->having([  ['cost', 100, '>='],
                        ['size' => '1L'] ], 'OR')
            ->get(['cost', 'size']));    
    
    }

    function joins(){
        $o = Database::table('other_permissions', 'op');
        $rows =   $o->join('folders', 'op.folder_id', '=',  'folders.id')
                    ->join('users', 'folders.belongs_to', '=', 'users.id')
                    ->join('user_role', 'users.id', '=', 'user_role.user_id')
                    //->join('roles', 'user_role.role_id', '=', 'roles.id') 
                    ->where([
                        ['guest', 1],
                        ['resource_table', 'products'],
                        ['r', 1]
                    ])->get();  
        
        Debug::debug($rows);
    }
 
    function get_nulls(){
        $conn    = Database::getConnection();
        $p = new ProductsModel($conn);
    
        Debug::debug($p->where(['workspace', NULL])->get());   
    }

    function get_users(){
        $conn    = Database::getConnection();
        $u = new UsersModel($conn);
    
        echo '<pre>';
        Factory::response()->setPretty(true)->send($u->fetchAll(null, ['id'=>'DESC']));
        echo '</pre>';
    }

    function get_user($id){
        $conn    = Database::getConnection();

        $u = new UsersModel($conn);
        $u->unhide(['password']);
        $u->hide(['firstname','lastname']);
        
        Debug::debug($u->where(['id'=>$id])->get());
    }

    function del_user($id){
        $conn    = Database::getConnection();

        $u = new UsersModel($conn);
        $ok = (bool) $u->where(['id' => $id])->delete(false);
        
        Debug::debug($ok);
    }

 
    function update_user($id) {
        $conn    = Database::getConnection();

        $u = new UsersModel($conn);
        $count = $u->where(['firstname' => 'HHH', 'lastname' => 'AAA', 'id' => 17])->update(['firstname'=>'Nico', 'lastname'=>'Buzzi', 'belongs_to' => 17]);
        
        Debug::debug($count);
    }

    function update_user2() 
    {
        $firstname = '';
        for ($i=0;$i<20;$i++)
            $firstname .= chr(rand(97,122));

        $lastname = strtoupper($firstname);    

        ////
        $conn    = Database::getConnection();

        $u = new UsersModel($conn);

        // implementar !!!
        $ok = $u->where([ [ 'email', 'nano@'], ['deleted_at', NULL] ])
        ->update([ 
                    'firstname' => $firstname, 
                    'lastname' => $lastname
        ]);
        
        Debug::debug($ok);
    }

    function update_users() {
        $conn    = Database::getConnection();

        $u = new UsersModel($conn);
        $count = $u->where([ ['lastname', ['AAA', 'Buzzi']] ])->update(['firstname'=>'Nicos']);
        
        Debug::debug($count);
    }

    function create_user($email, $password, $firstname, $lastname)
     {        
        for ($i=0;$i<20;$i++)
            $email = chr(rand(97,122)) . $email;
        
        $conn    = Database::getConnection();
        
        $u = new UsersModel($conn);
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
        $count = $p->where([['cost', 100, '<'], ['belongs_to', 90]])->update(['cost' => 100]);
        
        Debug::debug($count);
    }

    function respuesta(){
        Factory::response()->sendError('Acceso no autorizado', 401, 'Header vacio');
    }
   
    // ok
    function sender(){
        Debug::debug(Utils::send_mail('boctulus@gmail.com', 'Pablo ZZ', 'Pruebita', 'Hola!<p/>Esto es una <b>prueba</b><p/>Chau'));     
    }

    function test(){
        $ok = (bool) Database::table('messages')->create([
                'from_email' => '@',
                'from_name' => '',
                'to_email' => 'boctulus@gmail.com', 
                'to_name' => 'Pablo ZZ', 
                'subject' => 'Pruebita', 
                'body' => 'Hola!<p/>Esto es una <b>prueba</b><p/>Chau'
        ]);
        
        var_dump($ok);
    }
}