<?php

require_once "my_controller.php";

class DumbController extends MyController
{

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
        $res = (int) Request::getParam(0) / (int) Request::getParam(1);
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

}