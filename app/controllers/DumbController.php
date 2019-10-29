<?php

namespace simplerest\controllers;

use simplerest\core\Request;
use simplerest\libs\Database;
use simplerest\models\UsersModel;
use simplerest\models\ProductsModel;
use simplerest\libs\Factory;
use simplerest\libs\Debug;
use simplerest\core\Controller;
use simplerest\models\UserRoleModel;

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
        
        $p = new ProductsModel($conn);
        //$p->showDeleted(); 

        Debug::debug($p->filter(null, [ 
            ['size', '3L']
        ]));

        /*
        Debug::debug($product->filter(null, [ 
                ['name', ['Vodka', 'Wisky', 'Tekila']], // IN 
                ['belongs_to', 90]
        ]));

        Debug::debug($product->filter(null, [ 
            ['name', ['CocaCola', 'PesiLoca']], 
            ['cost', 550, '>='],
            ['cost', [100, 200]]
        ], 'OR'));    

        Debug::debug($product->filter(null, [ 
            ['name', ['CocaCola', 'PesiLoca', 'Wisky', 'Vodka'], 'NOT IN']
        ]));

        // implicit 'AND'
        Debug::debug($product->filter(null, [ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ]));        

        Debug::debug($product->filter(null, [ 
            ['cost', 200, '>='],
            ['cost', 270, '<=']
        ]));
        */
    }

    function joins(){
        $o = Database::table('other_permissions', 'op');
        $rows =   $o->join('folders', 'op.folder_id', '=',  'folders.id')
                    ->join('users', 'folders.belongs_to', '=', 'users.id')
                    ->join('user_role', 'users.id', '=', 'user_role.user_id')
                    ->join('roles', 'user_role.role_id', '=', 'roles.id') 
                    ->filter(null, [
                        ['guest', 1],
                        ['resource_table', 'products'],
                        ['r', 1]
                    ]);  
        
        Debug::debug($rows);
    }
 
    function get_nulls(){
        $conn    = Database::getConnection();
        $product = new ProductsModel($conn);
    
        Debug::debug($product->filter(null, ['workspace', NULL]));   
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
        $u->id = $id;
        $u->fetch();

        Debug::debug($u);
    }

    function del_user($id){
        $conn    = Database::getConnection();

        $u = new UsersModel($conn);
        $u->id = $id;
        $u->delete();
        $u->filter(null,['id', $id]);

        Debug::debug($u);
    }

 
    function update_user($id) {
        $conn    = Database::getConnection();

        $u = new UsersModel($conn);
        //$u->unfill(['lastname']);
        $u->where(['id', $id]);
        $ok = $u->update(['firstname'=>'PaulinoxxxxxyyzTTT', 'lastname'=>'Bozzoxx000555zZ']);
        
        Debug::debug($ok);
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

    function create_userrole($user_id, $role_id = 1){
        $conn = $this->getConnection();

        $ur = new UserRoleModel($conn);
        $id = $ur->create([ 'user_id' => $user_id, 'role_id' => $role_id ]);
        echo $id;
    }

    function restore($id){
        $conn    = Database::getConnection();

        $p = new ProductsModel($conn);        
        $p->id = $id;
        $ok = $p->restore();

        var_dump($ok);
    }

    /*
        JWT token for email confirmation & remember me
    */
    protected function gen_jwt(string $email, string $secret_key, string $encryption, string $exp_time){
        $time = time();

        $payload = [
            'alg' => $encryption,
            'typ' => 'JWT',
            'iat' => $time, 
            'exp' => $time + $exp_time,
            'email' => $email
        ];

        return \Firebase\JWT\JWT::encode($payload, $secret_key,  $encryption);
    }

    function test(){    
        $email = 'boctulus@gmail.com';
        ////
        $u = Database::table('users');
		$rows = $u->filter(['id'], ['email', $email]);

		if (count($rows) === 0)
			Factory::response()->send([]);

		$exp = time() + $this->config['email']['expires_in'];	

        $base_url =  (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $_SERVER['HTTP_HOST'];

        $token = $this->gen_jwt($email, $this->config['email']['secret_key'], $this->config['email']['encryption'], $this->config['email']['expires_in'] );
        echo $url = $base_url . '?token=' . $token . '&expires=' . $exp; 
    }

    
    function confirm_email($jwt, $exp){

		if ((int) $exp < time())
            $error = 'Token expired';
            
        if($jwt != null)
        {
            try{
                // Checking for token invalidation or outdated token
                
                $payload = \Firebase\JWT\JWT::decode($jwt, $this->config['email']['secret_key'], [ $this->config['email']['encryption'] ]);
                
                if (empty($payload))
                    Factory::response()->sendError('Unauthorized!',401);                     

                if (empty($payload->email)){
                    Factory::response()->sendError('email is needed',400);
                }

                if ($payload->exp < time())
                    Factory::response()->sendError('Token expired',401);
                
                echo $payload->email;

            } catch (\Exception $e) {
                /*
                * the token was not able to be decoded.
                * this is likely because the signature was not able to be verified (tampered token)
                *
                * reach this point if token is empty or invalid
                */
                Factory::response()->sendError($e->getMessage(),401);
            }	
        }else{
            Factory::response()->sendError('Authorization jwt token not found',400);
        }     
    }

}