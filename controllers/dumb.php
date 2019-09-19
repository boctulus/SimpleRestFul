<?php

namespace Controllers;

class DumbController extends MyController
{
    function sum($a, $b){
        $res = (int) $a + (int) $b;
        return  "$a + $b = " . $res;
    }

    function mul(){
        $req = \Core\Request::getInstance();
        $res = (int) $req[0] * (int) $req[1];
        return "$req[0] + $req[1] = " . $res;
    }

    function div(){
        $res = (int) @\Core\Request::getParam(0) / (int) @\Core\Request::getParam(1);
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
        include LIBS_PATH . 'database.php';

        $conn    = \Libs\Database::getConnection($this->config['database']);
        $product = new \Models\ProductsModel($conn);
    
        debug($product->fetchAll());
    }

    function get_users(){
        include LIBS_PATH . 'database.php';

        $conn    = \Libs\Database::getConnection($this->config['database']);
        $u = new \Models\UsersModel($conn);
    
        debug($u->fetchAll(null, ['id'=>'DESC']));
    }

    function get_user($id){
        include LIBS_PATH . 'database.php';
        $conn    = \Libs\Database::getConnection($this->config['database']);

        $u = new \Models\UsersModel($conn);
        $u->unhide(['password']);
        $u->hide(['firstname','lastname']);
        $u->id = $id;
        $u->fetch();

        debug($u);
    }
 
    function update_user($id) {
        include LIBS_PATH . 'database.php';
        $conn    = \Libs\Database::getConnection($this->config['database']);

        $u = new \Models\UsersModel($conn);
        $u->unfill(['lastname']);
        $u->id = $id;
        $ok = $u->update(['firstname'=>'Paulinoxxx', 'lastname'=>'Bozzoxx']);
        
        debug($ok);
    }

    function create_user($email, $password, $firstname, $lastname)
     {        
        for ($i=0;$i<20;$i++)
            $email = chr(rand(97,122)) . $email;
        
        include LIBS_PATH . 'database.php';
        $conn    = \Libs\Database::getConnection($this->config['database']);
        
        $u = new \Models\UsersModel($conn);
        //$u->fill(['lastname']);
        //$u->unfill(['password']);
        $id = $u->create(['email'=>$email, 'password'=>$password, 'firstname'=>$firstname, 'lastname'=>$lastname]);
        
        debug($id);
    }

}