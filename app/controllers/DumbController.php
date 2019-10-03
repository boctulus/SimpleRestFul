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
        $conn    = Database::getConnection($this->config['database']);
        $product = new ProductsModel($conn);
    
        Debug::debug($product->fetchAll());
    }

    function filter_products(){
        $conn    = Database::getConnection($this->config['database']);
        $product = new ProductsModel($conn);
    
        Debug::debug($product->filter(null, [ 
            ['name', ['CocaCola', 'PesiLoca']],  // IN 
            ['cost', 600, '>'],
            ['cost', [100,200]]
        ], 'OR'));    
        
        // implicit 'AND'
        Debug::debug($product->filter(null, [ 
            ['cost', 200, '<'],
            ['name', 'CocaCola'] 
        ]));
        
    }

    function get_users(){
        $conn    = Database::getConnection($this->config['database']);
        $u = new UsersModel($conn);
    
        Factory::response()->send($u->fetchAll(null, ['id'=>'DESC']));
    }

    function get_user($id){
        $conn    = Database::getConnection($this->config['database']);

        $u = new UsersModel($conn);
        $u->unhide(['password']);
        $u->hide(['firstname','lastname']);
        $u->id = $id;
        $u->fetch();

        \simplerest\libs\Debug::debug($u);
    }

 
    function update_user($id) {
        $conn    = Database::getConnection($this->config['database']);

        $u = new UsersModel($conn);
        //$u->unfill(['lastname']);
        $u->id = $id;
        $ok = $u->update(['firstname'=>'Paulinoxxx', 'lastname'=>'Bozzoxx000555']);
        
        Debug::debug($ok);
    }

    function create_user($email, $password, $firstname, $lastname)
     {        
        for ($i=0;$i<20;$i++)
            $email = chr(rand(97,122)) . $email;
        
        $conn    = Database::getConnection($this->config['database']);
        
        $u = new UsersModel($conn);
        //$u->fill(['email']);
        //$u->unfill(['password']);
        $id = $u->create(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname]);
        
        Debug::debug($id);
    }

    function create_userrole($user_id, $role_id = 1){
        $conn = $this->getConnection();

        $ur = new UserRoleModel($conn);
        $id = $ur->create([ 'user_id' => $user_id, 'role_id' => $role_id, 'creation_date'=> time() ]);
        echo $id;
    }


}